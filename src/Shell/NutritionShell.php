<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Shell;

use Cake\Console\ConsoleOptionParser;
use Cake\Console\Shell;
use Cake\Log\Log;
use Psy\Shell as PsyShell;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
/**
 * Simple console wrapper around Psy\Shell.
 */
class NutritionShell extends Shell
{

    /**
     * Start the shell and interactive console.
     *
     * @return int|null
     */
    public function main()
    {
        if (!class_exists('Psy\Shell')) {
            $this->err('<error>Unable to load Psy\Shell.</error>');
            $this->err('');
            $this->err('Make sure you have installed psysh as a dependency,');
            $this->err('and that Psy\Shell is registered in your autoloader.');
            $this->err('');
            $this->err('If you are using composer run');
            $this->err('');
            $this->err('<info>$ php composer.phar require --dev psy/psysh</info>');
            $this->err('');
            return self::CODE_ERROR;
        }

        $this->out("You can exit with <info>`CTRL-C`</info> or <info>`exit`</info>");
        $this->out('');

        Log::drop('debug');
        Log::drop('error');
        $this->_io->setLoggers(false);
        restore_error_handler();
        restore_exception_handler();

        $psy = new PsyShell();
        $psy->run();
    }

    /**
     * Display help for this console.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = new ConsoleOptionParser('console');
        $parser->description(
            'This shell provides a REPL that you can use to interact ' .
            'with your application in an interactive fashion. You can use ' .
            'it to run adhoc queries with your models, or experiment ' .
            'and explore the features of CakePHP and your application.' .
            "\n\n" .
            'You will need to have psysh installed for this Shell to work.'
        );
        return $parser;
    }
	
	public function notification()
    {
		$conn = ConnectionManager::get('default');
		date_default_timezone_set('Asia/Kolkata'); 

		$info = getdate();
		if($info['minutes'] <= 9){
			$min = 0;
			$minute = $min.$info['minutes'];
		}else{
			$minute = $info['minutes'];
		}
		$current_time = $info['hours']."-".$minute;
		$todayDate = date('Y-m-d');

		$day_name =  date('l',strtotime($todayDate));
		
		$nutrition = "SELECT gnd.id,gm.token,gm.isLogin,gm.device_type,gm.membership_valid_from,gm.membership_valid_to,gnd.day_name, gnd.nutrition_time, gnd.nutrition_value, gnd.nutrition_id, gn.user_id, gn.start_date, gn.expire_date FROM gym_nutrition_data gnd RIGHT JOIN gym_nutrition gn ON gn.id = gnd.nutrition_id RIGHT JOIN gym_member gm ON gm.id = gn.user_id WHERE start_date <= '".$todayDate."' AND expire_date >= '".$todayDate."' AND nutrition_time = '".$current_time."' AND gm.membership_valid_from <= '".$todayDate."' AND gm.membership_valid_to >= '".$todayDate."' AND day_name = '".$day_name."'";
		
		$nutrition_data = $conn->query($nutrition);
		$nutrition_result = $nutrition_data ->fetchAll('assoc');
		foreach($nutrition_result as $data)
		{
			
			if($data['token'] != '' && $data['isLogin'] == TRUE )
			{
				$token = $data['token'];
				$work_id = $data['id'];
				$title = 'Nutrition Reminder!';
				$text = 'Todays your nutrition is :'.$data['nutrition_time'].' '.$data['nutrition_value'];
				$bicon = 1;
				$data_key = 'title';
				$data_value = 'nutrition reminder';
				
				if($data['device_type'] == 'ios')
				{
					$send_notification = json_encode(array('registration_ids'=>array($token),'notification'=>array('title'=>$title,'text'=>$text,'badge'=>$bicon,'sound'=>1),'data'=>array($data_key=>$data_value)));
				}
				else
				{
					$send_notification = json_encode(array('registration_ids'=>array($token),'data'=>array($data_key=>$data_value,'message'=>$text,'work_id'=>$work_id)));
				}						
				$curl = curl_init();
					curl_setopt_array($curl, array(
					CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 300,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_POSTFIELDS => $send_notification,
					CURLOPT_SSL_VERIFYHOST=> 0,
					CURLOPT_SSL_VERIFYPEER=> 0,
					CURLOPT_HTTPHEADER => array(
						"authorization: key=AIzaSyBx5HbuFdDtIvhMGMl7rUk2V-sF-JmlRjM",
						"cache-control: no-cache",
						"content-type: application/json",
						"postman-token: ff7ad440-bbe0-6a2a-160d-83369683bc63"
					),
				));
				$response = curl_exec($curl);
				$err = curl_error($curl);
				curl_close($curl);
			}
		}
		die;
		//return $response;
	}
	
}
