<?php
/**
 * Local config for developer of environment.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

return [
    'language' => 'en',
    'components' => [
        'db' => [
    				'class' => '\yii\db\Connection',
    				'dsn' => 'mysql:host=localhost;dbname=grocery',
    				'username' => 'root',
    				'password' => '',
    				'charset' => 'utf8'
    		],
    		'mailer' => [
    				'class' => 'yii\swiftmailer\Mailer',
    				'viewPath' => '@common/mail',
    				'useFileTransport' => false,
    				'transport' => [
    						'class' => 'Swift_SmtpTransport',
    						'host' => 'mail.expertwebworx.in',
    						'username' => 'grocerdeals@expertwebworx.in',
    						'password' => '6d+1{E9#=ZW6',
    						'port' => '25',
    						'encryption' => 'tls',
    				],
    		],
    ],
];
