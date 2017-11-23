<?php
 class auth{
    private $options = [
        'restriction_msg' => "Vous n'avez pas le droit d'acceder à cette page"
    ];
    private $session;
 
    public function __construct($session, $options = []){
        $this->options = array_merge($this->options, $options);
        $this->session = $session;
    }
 
    public function hashPassword($password){
        return crypt($password, 'ratonlaveurs');
    }
 
    public function register($db, $username, $password, $email, $news){
        if($news == false){$news = 0;}else{$news = 1;}
        $pass = $password;
        $password = $this->hashPassword($password);
        $token = Str::random(60);
        $db->query('INSERT INTO users SET username = ?, password = ?, email = ?, confirmation_token = ?, newsletter = ?', [
            $username,
            $password,
            $email,
            $token,
            $news,
            $pass
        ]);
        $user_id = $db->lastInsertId();
        require_once('PHPMailerAutoload.php');
        //include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded
 
        $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
 
        $mail->IsSMTP(); // telling the class to use SMTP
 
        try {
            $mail->Host       = "in-v3.mailjet.com"; // SMTP server
            $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
            $mail->SMTPAuth   = true;                  // enable SMTP authentication
            $mail->Host       = "in-v3.mailjet.com"; // sets the SMTP server
            $mail->Port       = 25;                    // set the SMTP port for the GMAIL server
            $mail->Username   = "1c986fb9af19279c8e44396cba4e4c80"; // SMTP account username
            $mail->Password   = "db195d08d2b42213528e1d9803284dfb";        // SMTP account password
            $mail->AddReplyTo('berkill243@gmail.com', 'la16eme');
            $mail->AddAddress($email, 'la16eme.com');
            $mail->SetFrom('berkill243@gmail.com', 'la16eme');
            $mail->AddReplyTo('berkill243@gmail.com', 'la16eme');
            $mail->Subject = 'Validation de votre compte du si de la 16eme';
            $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
            $mail->MsgHTML("Validation de votre compte !<hr/> Bonjour $username,\r\nVous avez demandé à ouvrir un compte sur la16eme. Vous trouverez ci-dessous un lien vous permettant de finaliser votre inscription.<br/><br/>
            Si le lien ci-dessus ne fonctionne pas, vous pouvez copier / coller cette adresse directement dans la barre d'adresse de votre navigateur Internet afin de finaliser votre inscription.<br/><br/>
            Afin de valider votre compte merci de cliquer sur ce lien: <br/>http://www.la16eme2.freetzi.com/confirm.php?id=$user_id&token=$token<br/><br/><br/>
            Si nous n'avez pas demandé à créer un compte, veuillez ignorer ce mail.");
            $mail->AddAttachment('img/blond_allong.jpg');      // attachment
            $mail->Send();
            echo '<script language="Javascript">document.location.replace("http://www.la16eme2.freetzi.com/index.php");</script>';
            echo 'off';
            header('Location: ../index.php');
        } catch (phpmailerException $e) {
            $this->session->setFlash('danger', "Erreur !");
            App::redirect('index.php');
        } catch (Exception $e) {
            $this->session->setFlash('danger', "Erreur !");
            App::redirect('index.php');
        }
 
    }
 
    public function confirm($db, $user_id, $token){
        $user = $db->query('SELECT * FROM users WHERE id = ?', [$user_id])->fetch();
        if($user && $user->confirmation_token == $token ){
            $db->query('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = ?', [$user_id]);
            $this->session->write('auth', $user);
            return true;
        }
        return false;
    }
 
    public function restrict(){
        if(!$this->session->read('auth')){
            $this->session->setFlash('danger', $this->options['restriction_msg']);
            App::redirect('login.php');
            exit();
        }
    }
 
    public function user(){
        if(!isset($_SESSION['auth'])){
            return false;
        }
        return $this->session->read('auth');
    }
 
    public function connect($user){
        $this->session->write('auth', $user);
    }
 
    public function connectCookie($db){
        if(isset($_COOKIE['remember']) && !$this->user()){
            $remember_token = $_COOKIE['remember'];
            $parts = explode('==', $remember_token);
            $user_id = $parts[0];
            $user = $db->query('SELECT * FROM users WHERE id = ?', [$user_id])->fetch();
            if($user){
                $expected = $user_id . '==' . $user->remember_token . sha1($user_id . 'ratonlaveurs');
                if($expected == $remember_token){
                    $this->connect($user);
                    setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
                } else{
                    setcookie('remember', null, -1);
                }
            }else{
                setcookie('remember', null, -1);
            }
        }
 
    }
 
    public function login($db, $username, $password, $remember = false){
        $user = $db->query('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL', ['username' => $username])->fetch();
        if(isset($user->password)){
            if(crypt($password, 'ratonlaveurs') == $user->password){
                $this->connect($user);
                if($remember){
                    $a = $user->id;
                    $this->remember($db, $a);
                }
                return $user;
            }else{
                return false;
            }
        }
    }
 
    public function remember($db, $user_id){
        $remember_token = Str::random(60);
        $db->query('UPDATE users SET remember_token = ? WHERE id = ?',[$remember_token, $user_id]);
        setcookie('remember', $user_id . '==' . $remember_token . sha1($user_id . 'ratonlaveurs'), time() + 60 * 60 * 24 * 7);
    }
 
    public function logout(){
        setcookie('remember', NULL, -1);
        $this->session->delete('auth');
    }
 
    public function resetPassword($db, $email){
        $user = $db->query('SELECT * FROM users WHERE email = ? AND confirmed_at IS NOT NULL', [$email])->fetch();
        if($user){
            $reset_token = Str::random(60);
            $db->query('UPDATE users SET reset_token = ?, reset_at = NOW() WHERE id = ?', [$reset_token, $user->id]);
 
            require_once('PHPMailerAutoload.php');
 
 
            $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
 
            $mail->IsSMTP(); // telling the class to use SMTP
 
            try {
                $mail->Host       = "in-v3.mailjet.com"; // SMTP server
                $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
                $mail->SMTPAuth   = true;                  // enable SMTP authentication
                $mail->Host       = "in-v3.mailjet.com"; // sets the SMTP server
                $mail->Port       = 25;                    // set the SMTP port for the GMAIL server
                $mail->Username   = "1c986fb9af19279c8e44396cba4e4c80"; // SMTP account username
                $mail->Password   = "db195d08d2b42213528e1d9803284dfb";        // SMTP account password
                $mail->AddReplyTo('berkill243@gmail.com', 'la16eme');
                $mail->AddAddress($email, 'la16eme.com');
                $mail->SetFrom('berkill243@gmail.com', 'la16eme');
                $mail->AddReplyTo('berkill243@gmail.com', 'la16eme');
                $mail->Subject = 'Réinitialiser votre mot de passe du site de la 16eme';
                $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
                $mail->MsgHTML("Réinitialiser votre mot de passe !<hr/> Bonjour,\r\nVous avez demandé de changer de mot de passe sur la16eme. Vous trouverez ci-dessous un lien vous permettant de finaliser le tout.<br/><br/>
            Si le lien ci-dessus ne fonctionne pas, vous pouvez copier / coller cette adresse directement dans la barre d'adresse de votre navigateur Internet afin de finaliser votre inscription.<br/><br/>
            Afin de tout valider merci de cliquer sur ce lien: <br/>http://www.la16eme2.freetzi.com/reset.php?id={$user->id}&token=$reset_token<br/><br/><br/>
            Si nous n'avez pas demandé à créer un compte, veuillez ignorer ce mail.");
                $mail->AddAttachment('img/brun_allong.jpg');      // attachment
                $mail->Send();
                echo "Message Sent OK<p></p>\n";
                echo '<script language="Javascript">document.location.replace("http://www.la16eme2.freetzi.com/index.php");</script>';
            } catch (phpmailerException $e) {
                echo $e->errorMessage(); //Pretty error messages from PHPMailer
            } catch (Exception $e) {
                echo $e->getMessage(); //Boring error messages from anything else!
            }
            return $user;
        }
        return false;
    }
 
    public function checkResetToken($db, $user_id, $token){
        return $db->query('SELECT * FROM users WHERE id = ? AND reset_token IS NOT NULL AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)', [$user_id, $token])->fetch();
    }
 
    public function allow($db, $rang, $id, $errorMsg){
        $user = $db->query('SELECT * FROM role WHERE slug = ?', [$rang])->fetch();
        $user2 = $db->query('SELECT * FROM users WHERE username = ?', [$id])->fetch();
        if($user->level <= $user2->role_id){
 
        }else{
            $this->session->setFlash('danger', "Vous n'avez pas acces à cette page");
            App::redirect('index.php');
        }
    }
 
}