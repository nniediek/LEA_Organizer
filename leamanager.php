<?php
	include 'DBConnect.php';
class Leamanager{

	public $db;

	function __construct(){
		$this->db = new DBConnect();
	}

	function showForm(){
		
		echo '
		<!DOCTYPE html>


		<html>

			<head>
				<title> </title>
				<meta charset="UTF-8">
				<link href="style.css" rel="stylesheet" type="text/css"/>
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
					
					<input type="button" class="button_m floatR" value="Zur&uuml;ck"/>
					<div id="addLeaDiv">			
						<form id="addLeaForm" method="POST">
							<h1 id="title">LEA Titel</h1>
							<div id="available">
							Verf&uuml;gbare Klassen:					
							</div>
							<div id="selected">
								Ausgew&auml;hlte Klassen:
							</div>
							<div id="classes">
								<select id="aClasses" class="LeaFormLeft" size="3">
									<option>IBD2H16A</option>
									<option>IBW2H16A</option>
									<option>IBM2H16A</option>
								</select>
								<select id="rClasses" class="LeaFormRight" size="3">
									<option></option>
									<option></option>
									<option></option>
								</select>
							</div>
							
							<div id="time">
								<div class="div40 floatL">
									Von:<br/><input type="date" id="from"/>
								</div>
								<div class="div40 floatR">
									Bis:<br/><input type="date" id="till"/>
								</div>
							</div>
							
							<div id="milestones">
								<div id="ms_left">
									<select id="ms_list" class="LeaFormLeft" size="3">
										<option>Meilenstein 1</option>
										<option>Meilenstein 2</option>
										<option>Meilenstein 3</option>
									</select>
								</div>
								<div id="ms_right">
									<input type="button" id="AddMilestone" class="button_100_100" value="Meilenstein hinzuf&uuml;gen"/>
								</div>
							</div>
							<input type="hidden" name="do" value="saveLEA"/>
							<input type="submit" class="button_m" value="Speichern"/>
						</form>
						
					</div>
				</div>
			</body>
			
		</html>';
	}
	
	
	
	public function showHome(){
		echo'<!DOCTYPE html>

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
											&lt;ibd2h16ade&gt;
										</div>
									</div>
									<div class="logocont">
										<img src="img/logo.png" class="logo_small">
										<input type="button" id="logout" class="button_m" value="Logout">
									</div>
								<div style="clear: both"></div>
								</header>
								
								<div id="overview_panel">
									<p> Übersicht über die LEAs </p>
									</br>
									</br>
									</br>
								<form method="POST">
										<input type="hidden" name="do" value="addLEA"/>
										<input type="submit" id="add_lea" name="add" value="LEA hinzuf&uuml;gen" class="button_100">
								
								</form>
									<div class="LEAcontainer">
										<div class="LEA">
											<form method="POST">
												<p>LEA 1</p>
												<p class="date">&lt;12 Januar 2018 - 26 Januar 2018&gt;</p>
												<div class="buttons">
													<input type="submit" name="overview" value="LEA-&Uuml;bersicht" class="button_m">
													<input type="submit" name="edit" value="LEA bearbeiten" class="button_m">
												</div>
											</form>
										</div>
										<div class="LEA">
											<form action="POST">
												<p>LEA 1</p>
												<p class="date">&lt;12 Januar 2018 - 26 Januar 2018&gt;</p>
												<div class="buttons">
													<input type="submit" name="overview" value="LEA-&Uuml;bersicht" class="button_m">
													<input type="submit" name="edit" value="LEA bearbeiten" class="button_m">
												</div>
											</form>
										</div>
										<div class="LEA">
											<form action="POST">
												<p>LEA 1</p>
												<p class="date">&lt;12 Januar 2018 - 26 Januar 2018&gt;</p>
												<div class="buttons">
													<input type="submit" name="overview" value="LEA-&Uuml;bersicht" class="button_m">
													<input type="submit" name="edit" value="LEA bearbeiten" class="button_m">
												</div>
											</form>
										</div>
										<div class="LEA">
											<form action="POST">
												<p>LEA 1</p>
												<p class="date">&lt;12 Januar 2018 - 26 Januar 2018&gt;</p>
												<div class="buttons">
													<input type="submit" name="overview" value="LEA-&Uuml;bersicht" class="button_m">
													<input type="submit" name="edit" value="LEA bearbeiten" class="button_m">
												</div>
											</form>
										</div>
										<div class="LEA">
											<form action="POST">
												<p>LEA 1</p>
												<p class="date">&lt;12 Januar 2018 - 26 Januar 2018&gt;</p>
												<div class="buttons">
													<input type="submit" name="overview" value="LEA-&Uuml;bersicht" class="button_m">
													<input type="submit" name="edit" value="LEA bearbeiten" class="button_m">
												</div>
											</form>
										</div>
										<div class="LEA">
											<form action="POST">
												<p>LEA 1</p>
												<p class="date">&lt;12 Januar 2018 - 26 Januar 2018&gt;</p>
												<div class="buttons">
													<input type="submit" name="overview" value="LEA-&Uuml;bersicht" class="button_m">
													<input type="submit" name="edit" value="LEA bearbeiten" class="button_m">
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</body>
						
					</html>
		';
	}
	
	public function showCreateLea(){
		echo'<!DOCTYPE html>

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
								
								<input type="button" class="button_m floatR" value="Zur&uuml;ck"/>
								<div id="addLeaDiv">			
									<form id="addLeaForm" method="POST">
										<h1 id="title">LEA Titel</h1>
										<div id="available">
										Verf&uuml;gbare Klassen:					
										</div>
										<div id="selected">
											Ausgew&auml;hlte Klassen:
										</div>
										<div id="classes">
											<select id="aClasses" class="LeaFormLeft" size="3">
												<option>IBD2H16A</option>
												<option>IBW2H16A</option>
												<option>IBM2H16A</option>
											</select>
											<select id="rClasses" class="LeaFormRight" size="3">
												<option></option>
												<option></option>
												<option></option>
											</select>
										</div>
										
										<div id="time">
											<div class="div40 floatL">
												Von:<br/><input type="date" id="from"/>
											</div>
											<div class="div40 floatR">
												Bis:<br/><input type="date" id="till"/>
											</div>
										</div>
										
										<div id="milestones">
											<div id="ms_left">
												<select id="ms_list" class="LeaFormLeft" size="3">
													<option>Meilenstein 1</option>
													<option>Meilenstein 2</option>
													<option>Meilenstein 3</option>
												</select>
											</div>
											<div id="ms_right">
												<input type="button" id="AddMilestone" class="button_100_100" value="Meilenstein hinzuf&uuml;gen"/>
											</div>
										</div>
										
										<input type="submit" class="button_m" value="Speichern"/>
									</form>
									
								</div>
							</div>
						</body>
						
					</html>';
	}
	
	public function showLeaStatus (){
		echo' <!DOCTYPE html>

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
										&lt;ibd2h16ade&gt;
									</div>
								</div>
								<div class="logocont">
									<img src="img/logo.png" class="logo_small">
									<input type="button" id="logout" class="button_m" value="Logout">
								</div>
							<div style="clear: both"></div>
							</header>
								
							<div id="lea_overview">
									<div class="lea_title">
										LEA-Titel
									</div>
									<div id="cul">
										<input type="search" id="class_select" list="class_list" placeholder="Nach Klassen suchen...">
										<datalist id="class_list">
											<option value="Alle Klassen">Alle Klassen</option>
											<option value="ibd2h16a" class="class_option"></option>
											<option value="ibw2h16a" class="class_option"></option>
											<option value="ibm2h16a" class="class_option"></option>
										</datalist>
										</input>
									</div>
									<div style="clear: both"></div>
									<div id="ul">
										
										<table id="ms_table">
											<tr>
												<th>Meilenstein</th>
												<th>Deadline</th>
												<th>Abgaben</th>
											</tr>
											<tr class="ms_table_row">
												<td>&lt;Meilensteinbeschreibung&gt;</td>
												<td>13.11.2017</td>
												<td>80/120</td>
											</tr>
											<tr class="ms_table_row">
												<td>&lt;Meilensteinbeschreibung&gt;</td>
												<td>20.11.2017</td>
												<td>65/120</td>
											</tr>
											<tr class="ms_table_row">
												<td>&lt;Meilensteinbeschreibung&gt;</td>
												<td>20.11.2017</td>
												<td>65/120</td>
											</tr>
											<tr class="ms_table_row">
												<td>&lt;Meilensteinbeschreibung&gt;</td>
												<td>20.11.2017</td>
												<td>65/120</td>
											</tr>
											<tr class="ms_table_row">
												<td>&lt;Meilensteinbeschreibung&gt;</td>
												<td>20.11.2017</td>
												<td>65/120</td>
											</tr>
											<tr class="ms_table_row">
												<td>&lt;Meilensteinbeschreibung&gt;</td>
												<td>20.11.2017</td>
												<td>65/120</td>
											</tr>
											<tr class="ms_table_row">
												<td>&lt;Meilensteinbeschreibung&gt;</td>
												<td>20.11.2017</td>
												<td>65/120</td>
											</tr>
										</table>
									</div>
									
									<div id="ur">
									<h4>Erinnerungsmail für Meilensteine</h4>
									<hr/>
										<form id="autoform">
											<input type="checkbox" name="automail" value="automail">
											<label for="automail">Automatische Erinnerungsmail</label>
											<div style="clear: both"></div>
											<input type="number" name="days" value="days" min="0">
											<label for="days">Tage vor Abgabefrist</label>
										</form>
									</div>
								
								
									<div id="bl">
										<table id="teams_table">
											<th>Teams</th>
											<th>Abgaben</th>
											<tr class="teams_table_row">
												<td>
													Team 1 &lt;Mitglied 1, Mitglied 2, Mitglied 3&gt;
													<input type="submit" name="edit" value="Mail">
													<input type="submit" name="edit" value="Team angucken">
												</td>
												<td>
													2/3
												</td>
											</tr>
											<tr class="teams_table_row">
												<td>
													Team 1 &lt;Mitglied 1, Mitglied 2, Mitglied 3&gt;
													<input type="submit" name="edit" value="Mail" >
													<input type="submit" name="edit" value="Team angucken" >
												</td>
												<td>
													2/3
												</td>
											</tr>
											<tr class="teams_table_row">
												<td>
													Team 1 &lt;Mitglied 1, Mitglied 2, Mitglied 3&gt;
													<input type="submit" name="edit" value="Mail">
													<input type="submit" name="edit" value="Team angucken">
												</td>
												<td>
													2/3
												</td>
											</tr>
										</table>
									</div>
									
									<div id="br">
										<table id="teamless_table">
										<th>Teamlose Studis</th>
										<tr class="teamless_table_row">
											<td>Studi</td>
										</tr>
										<tr class="teamless_table_row">
											<td>Studi</td>
										</tr>
										<tr class="teamless_table_row">
											<td>Studi</td>
										</tr>
										<tr class="teamless_table_row">
											<td>Studi</td>
										</tr>
										<tr class="teamless_table_row">
											<td>Studi</td>
										</tr>
										<tr class="teamless_table_row">
											<td>Studi</td>
										</tr>
										<tr class="teamless_table_row">
											<td>Studi</td>
										</tr>
										<tr class="teamless_table_row">
											<td>Studi</td>
										</tr>
										<tr class="teamless_table_row">
											<td>Studi</td>
										</tr>
										</table>
									</div>
								
							</div>
						</div>
					</body>
					
				</html>';
	}
	
	public function showLeaHinzufuegen() {
		$this->db->connect();
	}
		
	public function error() {
		echo 'SOMETHING WENT WRONG';
	}
}
?>