<?php
	$version = phpversion()>= 5.6 ? "Pass":"Fail";
	$mbstring = extension_loaded('mbstring')== 0 ? "Fail" : "Pass";
	$intl = extension_loaded('intl')== 0 ? "Fail" : "Pass";
	$xml = extension_loaded('xml');
?>
<?= $this->Flash->render('error'); ?>
<body>	 
	<div class="pg-header">
		<h4 class="install_title"><span style="height:30px"><img  src="<?php echo $this->request->webroot;?>img/Thumbnail-img2.png" height="80%" /></span> <?php echo __("Gym Master - GYM Management Installation Wizard");?></h4>
	</div>
	<div class="step-content">
		<?php echo $this->Form->create("",["id"=>"install-form","class"=>"form-horizontal"]);?>
		<div>
			<!-- New Page Start -->
			<h3><?php echo __("Purchase Info");?></h3>
				<section>
					<h4><?php echo __("Purchase Imformation");?></h4>
					<hr/>
					<div class="form-group">	
						<label class="control-label col-md-3">
							<?php echo __("Server Name")?>
							<span class="text-danger"> *</span>
						</label>
						<div class="col-md-5">
							<div class="input text">
								<input type="text" id="domain_name" value="<?php echo $_SERVER['SERVER_NAME']; ?>" name="domain_name" class="form-control" disabled>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">
							<?php echo __("Purchase Key")?>
							<span class="text-danger"> *</span>
						</label>
						<div class="col-md-5">
							<div class="input text"><input type="text" id="purchaseKey" name="purchase_key" placeholder = "Enter Purchase Key" class="form-control required">
						</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">
							<?php echo __("E-Mail")?>
							<span class="text-danger"> *</span>
						</label>
						<div class="col-md-5">
							<div class="input text">
								<input type="text" id="email" name="purchase_email" placeholder = "Enter Purchase E-Mail" class="form-control required">
							</div>
						</div>
					</div>
							
					<div class="col-md-offset-3">
						<p> (*) <?php echo __("Fields are required.")?></p>
					</div>
				</section>
				
			<!-- New Page End -->
			<h3><?php echo __("Requirements");?></h3>
				<section>
					<h4><?php echo __("Server Requirements");?></h4>
					<p>If Any Result Fail, Then You Are Not Able To Install This Package.</p>
					<hr/>
					<div class="config">
						<table class="table table-strip">
							<thead>
							<tr>
								<th width="30%">Name</th>
								<th width="20%">Result</th>
								<th>Current configuration</th>
								<th>Required configuration</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td>PHP Version</td>
								<td><?php if($version == "Pass"){ ?>
									<button class="suc btn btn-success" disabled><?php echo $version; ?></button>
									<input type="text" name="version" class="form-control required msg" value="pass" readonly id="version">
									<?php }else{ ?>
									<button class="des btn btn-danger" disabled><?php echo $version; ?></button>
									<input type="text" name="version" class="form-control required msg" readonly>
									<?php }?>
								</td>
								<td>PHP <?php echo phpversion(); ?></td>
								<td>PHP 5.6.0 or greater</td>
							</tr>
							<tr>
								<td>mbstring extension</td>
								<td>
								<?php if($mbstring == "Pass"){ ?>
									<button class="suc btn btn-success" disabled><?php echo $mbstring; ?></button>
									<input type="text" name="mbstring" class="form-control required msg" value="pass" readonly>
									<?php }else{ ?>
									<button class="des btn btn-danger" disabled><?php echo $mbstring; ?></button>
									<input type="text" name="mbstring" class="form-control required msg" readonly>
								<?php }?>
								</td>
								<td><?php echo extension_loaded('mbstring')== 1 ? "Enable" : "Disable"; ?></td>
								<td>Enable</td>
							</tr>
							<tr>
								<td>intl extension</td>
								<td>
								<?php if($intl == "Pass"){ ?>
									<button class="suc btn btn-success" disabled><?php echo $intl; ?></button>
									<input type="text" name="intl" class="form-control required msg" value="pass" readonly>
									<?php }else{ ?>
									<button class="des btn btn-danger" disabled><?php echo $intl; ?></button>
									<input type="text" name="intl" class="form-control required msg" readonly>
									<?php }?>
								</td>
								<td><?php echo extension_loaded('intl')== 1 ? "Enable" : "Disable"; ?></td>
								<td>Enable</td>
							</tr>
							</tbody>
						</table>
					</div>
					
				
				</section>
			<h3><?php echo __("Database Setup");?></h3>
				<section>
					<h4><?php echo __("Database Setup");?></h4>
					<hr/>
					<div class="form-group">
						<label class="control-label col-md-3"><?php echo __("Database Name")?><span class="text-danger"> *</span></label>
						<div class="col-md-5">
						<div class="input text">
						<input type="text" name="db_name" class="form-control required">
						</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?php echo __("Database Username")?><span class="text-danger"> *</span></label>
						<div class="col-md-5">
						<div class="input text">
						<input type="text" name="db_username" class="form-control required">
						</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?php echo __("Database Password")?></label>
						<div class="col-md-5">
						<div class="input text">
						<input type="text" name="db_pass" class="form-control">
						</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?php echo __("Host")?><span class="text-danger"> *</span></label>
						<div class="col-md-5">
						<div class="input text">
						<input type="text" name="db_host" class="form-control required">
						</div>
						</div>
					</div>
					<div class="col-md-offset-3">
						<p> (*) <?php echo __("Fields are required.")?></p>
					</div>
				</section>
			<h3><?php echo __("System Setting")?></h3>
				<section> 
					<h4><?php echo __("System Setting")?></h4>
					<hr/>
					<div class="form-group">
						<label class="control-label col-md-3"><?php echo __("System Name")?><span class="text-danger"> *</span></label>
						<div class="col-md-8">
						<div class="input text">
						<input type="text" name="name" class="form-control required" value="GYM Master - GYM Management System">
						</div>
						</div>
					</div>		  		  
					<div class="form-group">
						<label class="control-label col-md-3"><?php echo __("Country")?></label>
						<div class="col-md-8">			
							<select id="country" class="form-control required" name="country">
									<option value="in">India</option>
									<option value="af">Afghanistan</option>
									<option value="al">Albania</option>
									<option value="dz">Algeria</option>
									<option value="ad">Andorra</option>
									<option value="ao">Angola</option>
									<option value="aq">Antarctica</option>
									<option value="ar">Argentina</option>
									<option value="am">Armenia</option>
									<option value="aw">Aruba</option>
									<option value="au" selected="">Australia</option>
									<option value="at">Austria</option>
									<option value="az">Azerbaijan</option>
									<option value="bh">Bahrain</option>
									<option value="bd">Bangladesh</option>
									<option value="by">Belarus</option>
									<option value="be">Belgium</option>
									<option value="bz">Belize</option>
									<option value="bj">Benin</option>
									<option value="bt">Bhutan</option>
									<option value="bo">Bolivia, Plurinational State Of</option>
									<option value="ba">Bosnia And Herzegovina</option>
									<option value="bw">Botswana</option>
									<option value="br">Brazil</option>
									<option value="bn">Brunei Darussalam</option>
									<option value="bg">Bulgaria</option>
									<option value="bf">Burkina Faso</option>
									<option value="mm">Myanmar</option>
									<option value="bi">Burundi</option>
									<option value="cm">Cameroon</option>
									<option value="ca">Canada</option>
									<option value="cv">Cape Verde</option>
									<option value="cf">Central African Republic</option>
									<option value="td">Chad</option>
									<option value="cl">Chile</option>
									<option value="cn">China</option>
									<option value="cx">Christmas Island</option>
									<option value="cc">Cocos (keeling) Islands</option>
									<option value="co">Colombia</option>
									<option value="km">Comoros</option>
									<option value="cg">Congo</option>
									<option value="cd">Congo, The Democratic Republic Of The</option>
									<option value="ck">Cook Islands</option>
									<option value="cr">Costa Rica</option>
									<option value="hr">Croatia</option>
									<option value="cu">Cuba</option>
									<option value="cy">Cyprus</option>
									<option value="cz">Czech Republic</option>
									<option value="dk">Denmark</option>
									<option value="dj">Djibouti</option>
									<option value="tl">Timor-leste</option>
									<option value="ec">Ecuador</option>
									<option value="eg">Egypt</option>
									<option value="sv">El Salvador</option>
									<option value="gq">Equatorial Guinea</option>
									<option value="er">Eritrea</option>
									<option value="ee">Estonia</option>
									<option value="et">Ethiopia</option>
									<option value="fk">Falkland Islands (malvinas)</option>
									<option value="fo">Faroe Islands</option>
									<option value="fj">Fiji</option>
									<option value="fi">Finland</option>
									<option value="fr">France</option>
									<option value="pf">French Polynesia</option>
									<option value="ga">Gabon</option>
									<option value="gm">Gambia</option>
									<option value="ge">Georgia</option>
									<option value="de">Germany</option>
									<option value="gh">Ghana</option>
									<option value="gi">Gibraltar</option>
									<option value="gr">Greece</option>
									<option value="gl">Greenland</option>
									<option value="gt">Guatemala</option>
									<option value="gn">Guinea</option>
									<option value="gw">Guinea-bissau</option>
									<option value="gy">Guyana</option>
									<option value="ht">Haiti</option>
									<option value="hn">Honduras</option>
									<option value="hk">Hong Kong</option>
									<option value="hu">Hungary</option>
									<option value="id">Indonesia</option>
									<option value="ir">Iran, Islamic Republic Of</option>
									<option value="iq">Iraq</option>
									<option value="ie">Ireland</option>
									<option value="im">Isle Of Man</option>
									<option value="il">Israel</option>
									<option value="it">Italy</option>
									<option value="ci">Côte D'ivoire</option>
									<option value="jp">Japan</option>
									<option value="jo">Jordan</option>
									<option value="kz">Kazakhstan</option>
									<option value="ke">Kenya</option>
									<option value="ki">Kiribati</option>
									<option value="kw">Kuwait</option>
									<option value="kg">Kyrgyzstan</option>
									<option value="la">Lao People's Democratic Republic</option>
									<option value="lv">Latvia</option>
									<option value="lb">Lebanon</option>
									<option value="ls">Lesotho</option>
									<option value="lr">Liberia</option>
									<option value="ly">Libya</option>
									<option value="li">Liechtenstein</option>
									<option value="lt">Lithuania</option>
									<option value="lu">Luxembourg</option>
									<option value="mo">Macao</option>
									<option value="mk">Macedonia, The Former Yugoslav Republic Of</option>
									<option value="mg">Madagascar</option>
									<option value="mw">Malawi</option>
									<option value="my">Malaysia</option>
									<option value="mv">Maldives</option>
									<option value="ml">Mali</option>
									<option value="mt">Malta</option>
									<option value="mh">Marshall Islands</option>
									<option value="mr">Mauritania</option>
									<option value="mu">Mauritius</option>
									<option value="yt">Mayotte</option>
									<option value="mx">Mexico</option>
									<option value="fm">Micronesia, Federated States Of</option>
									<option value="md">Moldova, Republic Of</option>
									<option value="mc">Monaco</option>
									<option value="mn">Mongolia</option>
									<option value="me">Montenegro</option>
									<option value="ma">Morocco</option>
									<option value="mz">Mozambique</option>
									<option value="na">Namibia</option>
									<option value="nr">Nauru</option>
									<option value="np">Nepal</option>
									<option value="nl">Netherlands</option>
									<option value="nc">New Caledonia</option>
									<option value="nz">New Zealand</option>
									<option value="ni">Nicaragua</option>
									<option value="ne">Niger</option>
									<option value="ng">Nigeria</option>
									<option value="nu">Niue</option>
									<option value="kp">Korea, Democratic People's Republic Of</option>
									<option value="no">Norway</option>
									<option value="om">Oman</option>
									<option value="pk">Pakistan</option>
									<option value="pw">Palau</option>
									<option value="pa">Panama</option>
									<option value="pg">Papua New Guinea</option>
									<option value="py">Paraguay</option>
									<option value="pe">Peru</option>
									<option value="ph">Philippines</option>
									<option value="pn">Pitcairn</option>
									<option value="pl">Poland</option>
									<option value="pt">Portugal</option>
									<option value="pr">Puerto Rico</option>
									<option value="qa">Qatar</option>
									<option value="ro">Romania</option>
									<option value="ru">Russian Federation</option>
									<option value="rw">Rwanda</option>
									<option value="bl">Saint Barthélemy</option>
									<option value="ws">Samoa</option>
									<option value="sm">San Marino</option>
									<option value="st">Sao Tome And Principe</option>
									<option value="sa">Saudi Arabia</option>
									<option value="sn">Senegal</option>
									<option value="rs">Serbia</option>
									<option value="sc">Seychelles</option>
									<option value="sl">Sierra Leone</option>
									<option value="sg">Singapore</option>
									<option value="sk">Slovakia</option>
									<option value="si">Slovenia</option>
									<option value="sb">Solomon Islands</option>
									<option value="so">Somalia</option>
									<option value="za">South Africa</option>
									<option value="kr">Korea, Republic Of</option>
									<option value="es">Spain</option>
									<option value="lk">Sri Lanka</option>
									<option value="sh">Saint Helena, Ascension And Tristan Da Cunha</option>
									<option value="pm">Saint Pierre And Miquelon</option>
									<option value="sd">Sudan</option>
									<option value="sr">Suriname</option>
									<option value="sz">Swaziland</option>
									<option value="se">Sweden</option>
									<option value="ch">Switzerland</option>
									<option value="sy">Syrian Arab Republic</option>
									<option value="tw">Taiwan, Province Of China</option>
									<option value="tj">Tajikistan</option>
									<option value="tz">Tanzania, United Republic Of</option>
									<option value="th">Thailand</option>
									<option value="tg">Togo</option>
									<option value="tk">Tokelau</option>
									<option value="to">Tonga</option>
									<option value="tn">Tunisia</option>
									<option value="tr">Turkey</option>
									<option value="tm">Turkmenistan</option>
									<option value="tv">Tuvalu</option>
									<option value="ae">United Arab Emirates</option>
									<option value="ug">Uganda</option>
									<option value="gb">United Kingdom</option>
									<option value="uy">Uruguay</option>
									<option value="us">United States</option>
									<option value="uz">Uzbekistan</option>
									<option value="vu">Vanuatu</option>
									<option value="va">Holy See (vatican City State)</option>
									<option value="ve">Venezuela, Bolivarian Republic Of</option>
									<option value="vn">Viet Nam</option>
									<option value="wf">Wallis And Futuna</option>
									<option value="ye">Yemen</option>
									<option value="zm">Zambia</option>
									<option value="zw">Zimbabwe</option>
					
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Select Currency<span class="text-danger"> *</span></label>
						<div class="col-md-8">
							<select class="form-control" name="currency">				
								<option value="USD">United States Dollar</option>
								<option value="ALL">Albania Lek</option>				
								<option value="AFN">Afghanistan Afghani</option>				
								<option value="ARS">Argentina Peso</option>				
								<option value="AWG">Aruba Guilder</option>				
								<option value="AUD">Australia Dollar</option>				
								<option value="AZN">Azerbaijan New Manat</option>				
								<option value="BSD">Bahamas Dollar</option>				
								<option value="BBD">Barbados Dollar</option>				
								<option value="BYR">Belarus Ruble</option>				
								<option value="BZD">Belize Dollar</option>				
								<option value="BMD">Bermuda Dollar</option>				
								<option value="BOB">Bolivia Boliviano</option>				
								<option value="BAM">Bosnia and Herzegovina Convertible Marka</option>				
								<option value="BWP">Botswana Pula</option>				
								<option value="BGN">Bulgaria Lev</option>				
								<option value="BRL">Brazil Real</option>				
								<option value="BND">Brunei Darussalam Dollar</option>				
								<option value="KHR">Cambodia Riel</option>				
								<option value="CAD">Canada Dollar</option>				
								<option value="KYD">Cayman Islands Dollar</option>				
								<option value="CLP">Chile Peso</option>				
								<option value="CNY">China Yuan Renminbi</option>				
								<option value="COP">Colombia Peso</option>				
								<option value="CRC">Costa Rica Colon</option>				
								<option value="HRK">Croatia Kuna</option>				
								<option value="CUP">Cuba Peso</option>				
								<option value="CZK">Czech Republic Koruna</option>				
								<option value="DKK">Denmark Krone</option>				
								<option value="DOP">Dominican Republic Peso</option>				
								<option value="XCD">East Caribbean Dollar</option>				
								<option value="EGP">Egypt Pound</option>				
								<option value="SVC">El Salvador Colon</option>				
								<option value="EEK">Estonia Kroon</option>				
								<option value="EUR">Euro Member Countries</option>				
								<option value="FKP">Falkland Islands (Malvinas) Pound</option>				
								<option value="FJD">Fiji Dollar</option>				
								<option value="GHC">Ghana Cedis</option>				
								<option value="GIP">Gibraltar Pound</option>				
								<option value="GTQ">Guatemala Quetzal</option>				
								<option value="GGP">Guernsey Pound</option>				
								<option value="GYD">Guyana Dollar</option>				
								<option value="HNL">Honduras Lempira</option>				
								<option value="HKD">Hong Kong Dollar</option>				
								<option value="HUF">Hungary Forint</option>				
								<option value="ISK">Iceland Krona</option>				
								<option value="INR">India Rupee</option>				
								<option value="IDR">Indonesia Rupiah</option>				
								<option value="IRR">Iran Rial</option>				
								<option value="IMP">Isle of Man Pound</option>				
								<option value="ILS">Israel Shekel</option>				
								<option value="JMD">Jamaica Dollar</option>				
								<option value="JPY">Japan Yen</option>				
								<option value="JEP">Jersey Pound</option>				
								<option value="KZT">Kazakhstan Tenge</option>				
								<option value="KPW">Korea (North) Won</option>				
								<option value="KRW">Korea (South) Won</option>				
								<option value="KGS">Kyrgyzstan Som</option>				
								<option value="LAK">Laos Kip</option>				
								<option value="LVL">Latvia Lat</option>				
								<option value="LBP">Lebanon Pound</option>				
								<option value="LRD">Liberia Dollar</option>				
								<option value="LTL">Lithuania Litas</option>				
								<option value="MKD">Macedonia Denar</option>				
								<option value="MYR">Malaysia Ringgit</option>				
								<option value="MUR">Mauritius Rupee</option>				
								<option value="MXN">Mexico Peso</option>				
								<option value="MNT">Mongolia Tughrik</option>				
								<option value="MZN">Mozambique Metical</option>				
								<option value="NAD">Namibia Dollar</option>				
								<option value="NPR">Nepal Rupee</option>				
								<option value="ANG">Netherlands Antilles Guilder</option>				
								<option value="NZD">New Zealand Dollar</option>				
								<option value="NIO">Nicaragua Cordoba</option>				
								<option value="NGN">Nigeria Naira</option>				
								<option value="KPW">Korea (North) Won</option>				
								<option value="NOK">Norway Krone</option>				
								<option value="OMR">Oman Rial</option>				
								<option value="PKR">Pakistan Rupee</option>				
								<option value="PAB">Panama Balboa</option>				
								<option value="PYG">Paraguay Guarani</option>				
								<option value="PEN">Peru Nuevo Sol</option>				
								<option value="PHP">Philippines Peso</option>				
								<option value="PLN">Poland Zloty</option>				
								<option value="QAR">Qatar Riyal</option>				
								<option value="RON">Romania New Leu</option>				
								<option value="RUB">Russia Ruble</option>				
								<option value="SHP">Saint Helena Pound</option>				
								<option value="SAR">Saudi Arabia Riyal</option>				
								<option value="RSD">Serbia Dinar</option>				
								<option value="SCR">Seychelles Rupee</option>				
								<option value="SGD">Singapore Dollar</option>				
								<option value="SBD">Solomon Islands Dollar</option>				
								<option value="SOS">Somalia Shilling</option>				
								<option value="ZAR">South Africa Rand</option>				
								<option value="KRW">Korea (South) Won</option>				
								<option value="LKR">Sri Lanka Rupee</option>				
								<option value="SEK">Sweden Krona</option>				
								<option value="CHF">Switzerland Franc</option>				
								<option value="SRD">Suriname Dollar</option>				
								<option value="SYP">Syria Pound</option>				
								<option value="TWD">Taiwan New Dollar</option>				
								<option value="THB">Thailand Baht</option>				
								<option value="TTD">Trinidad and Tobago Dollar</option>				
								<option value="TRY">Turkey Lira</option>				
								<option value="TRL">Turkey Lira</option>				
								<option value="TVD">Tuvalu Dollar</option>				
								<option value="UAH">Ukraine Hryvna</option>				
								<option value="GBP">United Kingdom Pound</option>									
								<option value="UYU">Uruguay Peso</option>				
								<option value="UZS">Uzbekistan Som</option>				
								<option value="VEF">Venezuela Bolivar</option>				
								<option value="VND">Viet Nam Dong</option>				
								<option value="YER">Yemen Rial</option>				
								<option value="ZWD">Zimbabwe Dollar</option>				
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?php echo __("Email")?> <span class="text-danger">*</span></label>
						<div class="col-md-8">
							<div class="input text">
								<input type="text" name="email" class="form-control required email" value="">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?php echo __("Date Formate")?></label>
						<div class="col-md-8">
							<select name="date_format" class="form-control plan_list required">
								<option value="F j, Y" selected="selected"><?php echo date("F j, Y");?></option>
								<option value="Y-m-d"><?php echo date("Y-m-d");?></option>
								<option value="m/d/Y"><?php echo date("m/d/Y");?></option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?php echo __("System Language")?></label>
						<div class="col-md-8">
							<select id="lang-selector" name="sys_language" class="form-control">
								<option value="en">English/en</option>
								<option value="ar">Arabic/ar</option>
								<option value="zh_CN">Chinese/zh-CN</option>
								<option value="cs">Czech/cs</option>
								<option value="fr">French/fr</option>
								<option value="de">German/de</option>
								<option value="el">Greek/el</option>					
								<option value="it">Italian/it</option>	
								<option value="ja">Japan/ja</option>
								<option value="pl">Polish/pl</option>
								<option value="pt_BR">Portuguese-BR/pt-BR</option>
								<option value="pt_PT">Portuguese-PT/pt-PT</option>						
								<option value="fa">Persian/fa</option>
								<option value="ru">Russian/ru</option>
								<option value="es">Spanish/es</option>											
								<option value="th">Thai/th</option>
								<option value="tr">Turkish/tr</option>
								<option value="ca">Catalan/ca</option>
								<option value="da">Danish/da</option>
								<option value="et">Estonian/et</option>
								<option value="fi">Finnish/fi</option>
								<option value="he">Hebrew (Israel)/he</option>
								<option value="hr">Croatian/hr</option>
								<option value="hu">Hungarian/hu</option>
								<option value="id">Indonesian/id</option>
								<option value="lt">Lithuanian/lt</option>
								<option value="nl">Dutch/nl</option>
								<option value="no">Norwegian/no</option>
								<option value="ro">Romanian/ro</option>
								<option value="sv">Swadish/sv</option>
								<option value="vi">Vietnamese/vi</option>
							</select>				
						</div>
					</div> 
					<div class="col-md-offset-3">
						<p> (*) <?php echo __("Fields are required.")?></p>
					</div>
				</section>  
			<h3><?php echo __("Login Details");?></h3>
				<section>
					<h4><?php echo __("Login Details");?></h4>
						<hr/>
						<div class="form-group">
							<label class="control-label col-md-3"><?php echo __("Username")?><span class="text-danger"> *</span></label>
							<div class="col-md-5">
							<div class="input text">
							<input type="text" name="lg_username" class="form-control required">
							</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?php echo __("Password")?><span class="text-danger"> *</span></label>
							<div class="col-md-5">
							<div class="input text">
							<input type="password" id="password" name="password" class="form-control required password">
							</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?php echo __("Confirm Password")?><span class="text-danger"> *</span></label>
							<div class="col-md-5">
							<div class="input text">
							<input type="password" name="confirm" id="confirm" class="form-control required">
							</div>
							</div>
						</div> 			
				</section>
			<h3><?php echo __("Finish");?></h3>
				<section id="final">
					<h4><?php echo __("Please Note :");?></h4>
					<hr/>					
					<p>
						<?php echo __("1. It may take couple of minutes to set-up database.");?>
					</p>			
					<p>
						<?php echo __("2. Do not refresh page after to you click on install button.");?>
					</p>
					<p>
						<?php echo __("3. You will be acknowledge with success message once after installation finishes.");?>
					</p>
					<p>
						<?php echo __("4. Click on install to complete the installation.")?>
					</p>
					
					<div id="loader" style="display:none;">
						<p>			
							<hr/>
							<h4>Please Wait System is now installing..</h4>
						</p>
						<span>
							<img src="<?php echo $this->request->base;?>/webroot/img/ajax-loader.gif" />
						</span>
					</div>
				</section>
    	</div>	
		<?php echo $this->Form->end();?>
	</div>

</body>
<script>
	$(function () {
		var form = $("#install-form");
		form.validate({
			errorPlacement: function errorPlacement(error, element) { element.before(error); },
			rules: {
				confirm: {
					equalTo: "#password"
				}
			}
		});
		form.children("div").steps({
			labels: {
				cancel: "Cancel",
				current: "current step:",
				pagination: "Pagination",
				finish: "Install Now",
				next: "Next Step",
				previous: "Previous Step",
				loading: "Loading ..."
			},	
			headerTag: "h3",
			bodyTag: "section",	
			transitionEffect: "slideLeft",
			onStepChanging: function (event, currentIndex, newIndex)
			{
				form.validate().settings.ignore = ":disabled,:hidden";
				return form.valid();
			},
			onFinishing: function (event, currentIndex)
			{
				$("#loader").css("display","block");
				form.validate().settings.ignore = ":disabled";
				return form.valid();
			},
			onFinished: function (event, currentIndex)
			{				
				form.submit();
			}
		});
	});

</script>