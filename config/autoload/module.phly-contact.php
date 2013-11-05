<?php
/**
 * This is a sample "local" configuration for your application. To use it, copy
 * it to your config/autoload/ directory of your application, and edit to suit
 * your application.
 *
 * This configuration example demonstrates using an SMTP mail transport, a
 * ReCaptcha CAPTCHA adapter, and setting the to and sender addresses for the
 * mail message.
 */
return array(

	'phly_contact' => array(

			'captcha' => array(
					'class' => 'dumb',
			),
			'form' => array(
					'name' => 'contact',
			),
			'mail_transport' => array(
					'class' => 'Zend\Mail\Transport\Sendmail',
					'options' => array(
					)
			),
			'message' => array(
				'to' => array(
					 		'steve@maraspin.net' => 'Steve',
					 ),
					'sender' => array(
							'address' => 'steve@maraspin.net',
							'name' => 'Steve',
					),
					'from' => array(
							'steve@maraspin.net' => 'Steve',
					),
			),
    ),
);
