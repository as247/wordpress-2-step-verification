<?php
/**
 * @var Wordpress2StepVerification $this
 */
?>
<!doctype html>
<html>
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <title><?php _e('Enter verification code', 'wordpress-2-step-verification'); ?></title>
    <link href="<?php $this->plugin_url('template/assets/css/wp2sv.css') ?>" rel="stylesheet">
</head>
<body>
<?php
    $method=$this->get_receive_method();
?>
<div id="wp2sv">
    <div class="header">
        <div class="logo-wrapper">
            <div class="logo"></div>
        </div>
        <div class="heading">
            <div class="h1">
                <h1><?php _e('2-Step Verification','wordpress-2-step-verification');?></h1>
            </div>
            <div class="h2">
                <?php switch ($method){
                    case 'others':
                        $h2=__('To sign in to your Wordpress Account, choose a task from the list below.','wordpress-2-step-verification');
                        break;
                    case 'recovery':
                        $h2=__('To recover access to your Wordpress Account, complete the task below.','wordpress-2-step-verification');
                        break;
                    default:
                        $h2=__('To help keep your account safer, complete the task below.','wordpress-2-step-verification');
                }?>
                <h2><?php echo $h2;?></h2>
            </div>
        </div>

    </div>

    <?php
    switch($method){
        case 'others':
            include(dirname(__FILE__).'/form-others.php');
            break;
        case 'recovery':
            include(dirname(__FILE__).'/form-recovery.php');
            break;
        default:
            include(dirname(__FILE__).'/form-verify.php');
    }
    ?>
    <div class="current-user">
        <span class="tac"><?php echo $this->user->user_login;?></span>
        <a href="<?php echo wp_logout_url();?>" class="logout"><?php _e('Use a different account','wordpress-2-step-verification');?></a>
    </div>



</div>
</body>
</html>