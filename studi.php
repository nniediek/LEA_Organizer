<?php 
	class Studi {
		
		
		public function showCreateTeam() {
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
						
						<form id="addTeam" method="get">	
							<h1>Team hinzufügen</h1>
							<div id="available">
								Verf&uuml;gbare Studenten:					
							</div>
							<div id="selected">
								Ausgew&auml;hlte Studenten:
							</div>
							<input type="search" name="students" list="students" placeholder="Studenten ausw&auml;hlen" id="aStudents" class="div40a" size="3">
							<datalist id="students">
								<option>Student 1</option>
								<option>Student 2</option>
								<option>Student 3</option>
							</datalist>
							<div id="move">
								<input type="button" id="moveBtn" class="button_s" value=">">
							</div>
							<select id="rStudents" name="rStudents" class="div40a" size="3" multiple>
								<option>S1</option>
								<option>S2</option>
								<option>S3</option>
							</select>
							<input type="hidden" name="do" value="createTeam"/>
							<input type="submit" id="submitTeam" class="button_l center" value="Team erstellen"/>
						</form>
					</div>
				</body>
				
			</html>';
		}
		
		
		public function showManageTeam(){
			echo'
			<!DOCTYPE html>

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
		
		public function error() {
			echo 'SOMETHING WENT WRONG';
		}
		
	}
?>
