<?php

require 'includes/MySQL.class.php';
require 'includes/CommonFunctions.php';
require 'includes/RealIP.class.php';

$ip = new ip();
$myip = $ip->checks_ip();

$dataset = new ASimpleMySQLDB(SIMPLE_DB_SERVER, SIMPLE_DB_NAME, SIMPLE_DB_USERNAME, SIMPLE_DB_PASSWORD);

// If the submit button is pressed, run this code

if(@$_POST['submit'])
{

	@$username = $_POST['username'];
	@$password = $_POST['password'];
	@$emailaddress = $_POST['emailaddress'];

	$encrypedPassword = md5($password);

	if($username == "")
	{

		print("Please enter your desired username.");

	}else{

		if($password == "")
		{

			print("Please enter a password for your account.");

		}else{

			if($emailaddress == "")
			{

				print("Please enter your email address.");

			}else{

				if(strlen($username) < 4)
				{

					print("Please enter a username with more than 4 characters.");

				}else{

					if(strlen($password) < 6)
					{

						print("Please enter a password with more than 4 characters.");

					}else{

						if(!is_valid_email($emailaddress))
						{

							print("The email address you entered is invalid.");

						}else{

							$checkUser = $dataset->get_record_by_ID('users', 'username', $username);

							if($checkUser['username'] == $username)
							{

								print("The username you entered is already registered. Please choose another username.");

							}else{

								$checkEmail = $dataset->get_record_by_ID('users', 'emailaddress', $emailaddress);

								if($checkEmail['emailaddress'] == $emailaddress)
								{

									print("The email address you entered is already registered. Please enter another email address.");

								}else{

									$inserts[] = array('username' => $username,'password' => $encrypedPassword,'emailaddress' => $emailaddress,'ipaddress' => $myip,'status' => 'active');
									foreach($inserts as $insert)
									{
										$dataset->insert_array('users', $insert);
									}

									print("Thank you $username. Your account has been created successfully.");

								}

							}

						}

					}

				}

			}

		}

	}

}else{

// Otherwise show the form...

	print("<form method=\"POST\" action=\"createaccount.php\">
	<table border=\"0\">
	<tr><td>Username:</td><td><input type=\"text\" name=\"username\" size=\"40\"></td></tr>
	<tr><td>Password:</td><td><input type=\"password\" name=\"password\" size=\"40\"></td></tr>
	<tr><td>Email Address:</td><td><input type=\"text\" name=\"emailaddress\" size=\"40\"></td></tr>
	<tr><td>Your IP:</td><td>$myip<br /><small>Your IP address has been logged for security purposes.</small></td></tr>
	<tr><td>&nbsp;</td><td><input type=\"submit\" name=\"submit\" value=\"Create Account\"></td></tr>
	</table>
	</form>");

}

?>
