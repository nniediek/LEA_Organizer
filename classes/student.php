<?php 
	class Student extends User{
		
		private $class; // saves the class of the student
		
		// constructor of the student
		public function __construct($username, $firstName, $lastName, $eMail, $class){
			parent::__construct($username, $firstName, $lastName, $eMail);
			$this->class = $class;
		}
		
		/* ------------------------------------------------------------------------------------ */
		
		public function showHome() {
			echo '<!DOCTYPE html>

<html>

	<head>
		<title> </title>
		<meta charset="UTF-8">
		<link href="CSS/style.css" rel="stylesheet" type="text/css"/>
	</head>
	
	<body>
		<div id="wrapper">
			<header>
				<div class="usercont">
					<p>Eingeloggt als:</p>
					<div class="username">
						&lt;Username&gt;
					</div>
				</div>
				<div class="logocont">
					<img src="img/logo.png" class="logo_small">
					<input type="button" id="logout" class="button_m" value="Logout">
				</div>
				<div style="clear: both"></div>
			</header>
			<div id="team_overview">
				<center><h1>Team verwalten</h1></center>
				<div id="team_div" class="div50a">
					<h2>Team</h2>
					<table>
						<tr>
							<td>Student 1</td>
						</tr>
						<tr>
							<td>Student 2</td>
						</tr>
						<tr>
							<td>Student 3</td>
						</tr>
					</table>
				</div>
				<div id="edit_div" class="div50a">
					<h2>&Uuml;bersicht</h2>
					<table>
						<tr>
							<td>Projekt Arbeitstitel <input type="button" class="button_s" value="+"/></td>
							<td><input type="text" disabled/></td>
						</tr>
						<tr>
							<td>Projekt Kurzbeschreibung <input type="button" class="button_s" value="+"/></td>
							<td><input type="text" disabled/></td>
						</tr>
						<tr>
							<td>Ausf&uuml;hrliche Beschreibung <input type="button" class="button_s" value="+"/></td>
							<td><input type="text" disabled/></td>
						</tr>
					</table>
				</div>
				<div id="data_div" class="div50a">
					<h2>Abgaben</h2>
					<table>
						<tr>
							<td>Meilenstein 1 <input type="button" class="button_s" value="..."/></td>
						</tr>
						<tr>
							<td>Meilenstein 2 <input type="button" class="button_s" value="..."/></td>
						</tr>
						<tr>
							<td>Meilenstein 3 <input type="button" class="button_s" value="..."/></td>
						</tr>
					</table>
				</div>
				<div id="upload_div" class="div50a">
					<h2>Hochladen</h2>
					<input type="button" class="button_l" value="Hochladen"/>
				</div>
				
				<div id="log_div" class="div100a">
					<h2>Logbuch</h2>
					<input type="search" id="log" list="log_list" placeholder="Eintrag ausw&auml;hlen..."/><br/><br/>
						<datalist id="log_list">
							<option>Eintrag 1</option>
							<option>Eintrag 2</option>
							<option>Eintrag 3</option>
							<option>Eintrag 4</option>
							<option>Eintrag 5</option>
							<option>Eintrag 6</option>
						</datalist>
					<input type="button" id="addLog" class="button_m" value="Eintrag hinzuf&uuml;gen"/>
				</div>
				<div style="clear: both"></div>
		    </div>
		</div>
	</body>
	
</html>';
		}
		
		public function showSite2() {
			echo '<p>
                    <h1> site 2 </h1>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Curabitur pretium tincidunt lacus. Nulla gravida orci a odio. 
                  </p>';
		}
		
		public function showSite3() {
			echo '<p>
                    <h1> site 3 </h1>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Curabitur pretium tincidunt lacus. Nulla gravida orci a odio. 
                  </p>';
		}
		
		public function error() {
			echo 'SOMETHING WENT WRONG';
		}
		
	}
?>
