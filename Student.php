<?php

namespace LEO;

use LEO\Model\Database;

class Student
{
	//database connection
	private $db;
	
	public function __construct() 
    {
        $this->db = new Database();
    }
	
	public function showHome(){
		$checkForProject = $this->db->getProject($_SESSION["userID"]);
		
		if($checkForProject == null)
			$this->showCreateTeam();
		else
			$this->showStudent();
	}
	
	//first view, if the student is not in a project yet
	public function showCreateTeam(){
		
		echo '<!DOCTYPE html>

			<html>

				<head>
					<title> </title>
					<meta charset="UTF-8">
					<link href="CSS/style.css" rel="stylesheet" type="text/css"/>
					<script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
					<script type="text/javascript" src="script/student.js"></script>
					
				</head>	
				<body>
					<div id="wrapper">';
					
		include 'header.php';
						
						
		//creates a team after pressing the submit button			
		if (isset($_POST["submitTeamForm"]) || $_SERVER["REQUEST_METHOD"] == "POST") {
			$teamMembers = $_POST["rStudents"];
			array_push($teamMembers, $_SESSION["username"]);
			if(count($teamMembers) >= 2 && count($teamMembers <= 3)){
				//array_push($teamMembers, "ibw2h16ace");
				$db = new Database();
				$db->createProject($teamMembers);
				header('Location: '. $_SERVER['PHP_SELF'] . '?controller=Student&do=showStudent');	
				die;
			}
			else{
				echo 'Fehler beim erstellen des Teams. Ein Team darf nur 2-3 Mitglieder haben';
			}		
		}
		else {
			
			//returns all members that are not in a team and in the same LEA
			$possibleMembers = $this->db->getPossibleTeamMembers($_SESSION["userID"]);
			echo '		<div id="addTeam">	
						<h1>Team hinzufügen</h1>
						<div id="available">
							Verf&uuml;gbare Studenten:					
						</div>
						<div id="selected">
							Ausgew&auml;hlte Studenten:
						</div>
						<input type="search" name="students" list="students" placeholder="Studenten ausw&auml;hlen" id="aStudents" class="div40a" size="3">
						<datalist id="students">';
					
						//inserts all possible team members into the datalist
						if ($possibleMembers != NULL){
							for ($i=0; $i<count($possibleMembers); $i++){
								echo "<option value=" .$possibleMembers[$i]["username"];
								echo ">";
								echo $possibleMembers[$i]["firstname"] . " "
									. $possibleMembers[$i]["lastname"];
								echo "</option>";
							}
						}
						else echo "<option>Keine verfügbaren Teammitglieder</option>";
						
						echo '</datalist>
							<div id="move">
								<input type="button" id="addStudent" class="button_s" value=">">
								<input type="button" id="delStudent" class="button_s" value="<">
							</div>
						
							<form id="submitTeamForm" method="post" onsubmit="selectAll()">
								<select id="rStudents" name="rStudents[]" class="div40a" multiple size="3">
								
								</select>
									<input type="hidden" name="controller" value="Student"/>
									<input type="hidden" name="do" value="showCreateTeam"/>
								<input type="submit" id="submitTeam" class="button_l center" value="Team erstellen"/>
							</form>
						</div>
					</div>
				</body>
			
			</html>';
		}
	}
	
	public function showStudent()
	{
		include 'header.php';
		
		//$students = $this->db->getProjectMembers($_SESSION["userID"]);
		$leaid = $this->db->getLeaID($_SESSION["userID"])->LEAID;
		$project = $this->db->getProject($leaid, $_SESSION["userID"]);
		$students = $this->db->getProjectMembers($_SESSION["userID"],$project->ID);
		$milestones = $this->db->getMilestones($leaid);
		$logbook = $this->db->getLogbook($project->ID);
		
		echo '<div id="team_overview">
				<center><h1>Team verwalten</h1></center>
				<div id="team_div" class="div50a">
					<h2>Team</h2>
					<table>';
					
				foreach($students as $row)
				{
					$student = $this->db->selectUserByID($row->STUDENTID);
					echo '<tr><td>'.$student->firstname.' '.$student->lastname.'</td></tr>';
				}
				
		echo '</table>
				</div>
				<div id="edit_div" class="div50a">
					<h2>&Uuml;bersicht</h2>
					<table>
						<tr>
							<td>Projekt Arbeitstitel <input type="button" class="button_s" value="+"/></td>
							<td><input type="text" value="'.$project->title.'"disabled/></td>
						</tr>
						<tr>
							<td>Projekt Kurzbeschreibung <input type="button" class="button_s" value="+"/></td>
							<td><input type="text" value="'.$project->task.'" disabled/></td>
						</tr>
						<tr>
							<td>Ausf&uuml;hrliche Beschreibung <input type="button" class="button_s" value="+"/></td>
							<td><input type="text" value="'.$project->idea_description.'" disabled/></td>
						</tr>
					</table>
				</div>
				<div id="data_div" class="div50a">
					<h2>Abgaben</h2>
				<table>';
					
				foreach($milestones as $row)
				{
					echo '<tr><td>Beschreibung: '.$row->description.' Abgabe: '.$row->deadline.'<input type="button" class="button_s" value="..."/></td></tr>';
				}
						
		echo	'</table>
					</div>
					<div id="upload_div" class="div50a">
						<h2>Hochladen</h2>
						<input type="button" class="button_l" value="Hochladen"/>
					</div>
					
					<div id="log_div" class="div100a">
						<h2>Logbuch</h2>
						<input type="search" id="log" list="log_list" placeholder="Eintrag ausw&auml;hlen..."/><br/><br/>
							<datalist id="log_list">';
				
				foreach($logbook as $row)
				{
					echo '<option>'.$row->entry.'</option>';
				}
								
		echo				'</datalist>
						<input type="button" id="addLog" class="button_m" value="Eintrag hinzuf&uuml;gen"/>
					</div>
					<div style="clear: both"></div>
				</div>
			</div>';
	}
}

?>