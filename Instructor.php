<?php

namespace LEO;

use LEO\Model\InstructorDatabase;

class Instructor
{
    private $db;

    public function __construct()
    {
        $this->db = new InstructorDatabase();
    }
	
	public function showHome()
	{
		if (!isset($_SESSION["permission"]) || $_SESSION["permission"] != 2) {
			echo "<br>no Permission to Instructor";
			return;
		}

        include 'header.php';
		
		// get supervised teams
		$supervisedTeams = $this->db->getManagedTeams();
		
		echo '<div id="team_overview">
				<center><h1>Team verwalten</h1></center>
				<div id="team_div" class="div100a">
					<h2>Team</h2>
					<table>';

		
		// show teams and a submit button with hidden value with the team id
		if (count($supervisedTeams) > 0){
			for ($i = 0; $i<count($supervisedTeams); $i++) {
				echo '<tr><td><form method="POST">'
					. $supervisedTeams[$i]['title'] . ' '
					. '<input type="submit" id="submitTeam" value="Team ansehen"/>'
					. '<input type="hidden" name="team" value="' . $supervisedTeams[$i]['ID'] . '"/>'
					. '<input type="hidden" name="controller" value="Instructor"/>'
					. '<input type="hidden" name="do" value="showTeam"/>'
					. '</form></td></tr>';
			}
		}
		else
			echo '<tr><td>Keine Teams werden zurzeit betreut</td></tr>';
		

		echo '</table>
			</div>
			</div>';
	}
	
	public function showTeam()
    {
		if (!isset($_SESSION["permission"]) || $_SESSION["permission"] != 2) {
			echo "<br>no Permission to Instructor";
			return;
		}

        include 'header.php';

        $project = $this->db->getProjectByTeamID();
        $students = $this->db->getProjectMembersByTeamID();
        $milestones = $this->db->getMilestonesByProjectID();
        $logbook = $this->db->getLogbook($_POST["team"]);
		
        echo '<div id="team_overview">
				<center><h1>Team verwalten</h1></center>
				<div id="team_div" class="div50a">
					<h2>Team</h2>
					<table>';

		if (count($students) > 0)
			foreach ($students as $row) {
				$student = $this->db->selectUserByID($row->STUDENTID);
				echo '<tr><td>' . $student->firstname . ' ' . $student->lastname . '</td></tr>';
			}
		else
			echo '<tr><td>Keine Teammitglieder vorhanden</td></tr>';
		
        echo '</table>
				</div>
				<div id="edit_div" class="div50a">
					<h2>&Uuml;bersicht</h2>
					<table>
						<tr>
							<td>Projekt Arbeitstitel
								<input type="button" id="edit__project__title" class="button_s button_edit" value="+" />
							</td>
							<td>
								<input type="text" id="titleDisplay" value="' . $this->showFirstLine($project->title) . '"disabled />
							</td>
						</tr>
						<tr>
							<td>Projekt Kurzbeschreibung
								<input type="button" id="edit__project__task" class="button_s button_edit" value="+" />
							</td>
							<td>
								<input type="text" id="taskDisplay" value="' . $this->showFirstLine($project->task) . '" disabled />
							</td>
						</tr>
						<tr>
							<td>Ausf&uuml;hrliche Beschreibung
								<input type="button" id="edit__project__idea_description" class="button_s button_edit" value="+" />
							</td>
							<td>
								<input type="text" id="idea_descriptionDisplay" value="' . $this->showFirstLine($project->idea_description) . '" disabled/>
							</td>
						</tr>
						<form id="editProjectForm" method="post">
					
							<div id="dialogbox"><!---Die zu öffnende box-->
								<div id="dialogboxcontent">
									<label id="popupTitle"></label>
									<span id="close" class="popUpClose" >close</span>
									<textarea id="titleBigText" class="bigTextArea" name="titleValue" disabled>' . $project->title . '</textarea>
									<textarea id="taskBigText" class="bigTextArea" name="taskValue" disabled>' . $project->task . '</textarea>
									<textarea id="idea_descriptionBigText" class="bigTextArea" name="idea_descriptionValue" disabled>' . $project->idea_description . '</textarea>
								</div>
							</div>
						</form>
					</table>
				</div>
				<div id="data_div" class="div50a">
					<h2>Abgaben</h2>
				<table>';

				if (count($milestones) > 0)
					foreach($milestones as $row)
					{
						echo '<tr>
							<td>Beschreibung: '.$row->description.' Abgabe: '.$row->deadline.'&#09;';
						
						if($this->db->checkFile($project->ID,$row->ID)){
							echo'<input type="button" value="&#10004" disabled/>
								</td></tr>';
						}
						else{
							echo'<input type="button" value="&#10008;" disabled/>
								</td></tr>';
						}
					}
				else
					echo '<tr><td>Keine Meilensteine vorhanden</td></tr>';

        echo '</table>
					</div>
					<div id="log_div" class="div100a">
						<h2>Logbuch</h2>
						<input type="search" id="log" list="log_list" placeholder="Eintrag ausw&auml;hlen..."/><br/><br/>
							<datalist id="log_list">';

		if (count($logbook) > 0)
			foreach ($logbook as $row) {
				echo '<option>' . $row->entry . '</option>';
			}
		else
			echo '<option>Kein Logbucheintrag vorhanden</option>';

        echo '			</datalist>
						<input type="button" id="addLog" class="button_m" value="Eintrag hinzuf&uuml;gen"/>
					</div>
					<div style="clear: both"></div>
				</div>
			</div>';
    }
	
    private function showFirstLine($text)
    {
        $line = substr($text, 0, strpos($text, PHP_EOL));
        if (strlen($line) == 0) $line = $text;
        return $line;
    }
}

?>