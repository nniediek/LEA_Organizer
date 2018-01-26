<?php

namespace LEO;

use LEO\Model\StudentDatabase;

class Student
{
    //database connection
    private $db;

    public function __construct()
    {
		if (!isset($_SESSION["permission"]) || $_SESSION["permission"] != 3 ) {
			return;
		}
        $this->db = new StudentDatabase();
    }

    public function showHome()
    {
		if (!isset($_SESSION["permission"]) || $_SESSION["permission"] != 3 ) {
			echo "<br>no Permission to Student   ".$_SESSION["permission"];
			return;
		}
		
        $checkForProject = $this->db->getProject($_SESSION["userID"]);

        if ($checkForProject == null)
            $this->showCreateTeam();
        else
            $this->showStudent();
    }

    //first view, if the student is not in a project yet
    public function showCreateTeam()
    {
		if (!isset($_SESSION["permission"]) || $_SESSION["permission"] != 3 ) {
			echo "<br>no Permission to Student";
			return;
		}
        include 'header.php';

        //creates a team after pressing the submit button
        if (isset($_POST["submitTeamForm"]) || $_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["rStudents"]) && $_POST["rStudents"][0] != null) {
                $teamMembers = $_POST["rStudents"];
                array_push($teamMembers, $_SESSION["username"]);

                $error = false;
                if (count($teamMembers) >= 2 && count($teamMembers <= 3)) {

                    for ($i = 0; $i < count($teamMembers); $i++) {
                        if ($this->db->isInProject($teamMembers[$i])) {
                            $error = true;
                            break;
                        }
                    }

                    if ($error == false) {
                        $this->db->createProject($teamMembers);
                        header('Location: ' . $_SERVER['PHP_SELF'] . '?controller=Student&do=showStudent');
                        die;
                    }
                } else {
                    $error = true;
                }
            } else $error = true;

            if ($error) {
                header('Location: ' . $_SERVER['PHP_SELF'] . '?controller=Student&do=showCreateTeam&error=true');
                die;
            }

        } else {

            //returns all members that are not in a team and in the same LEA:
            $possibleMembers = $this->db->getPossibleTeamMembers($_SESSION["userID"]);
            echo '		<div id="addTeam">	
						<h1>Team hinzufügen</h1>';
            if (isset($_GET["error"]) && $_GET["error"] == true) echo '<h4 style="color:red">Fehler beim Erstellen des Teams</h4>';
            echo '
						<div id="available">
							Verf&uuml;gbare Studenten:					
						</div>
						<div id="selected">
							Ausgew&auml;hlte Studenten:
						</div>
						<input type="search" name="students" list="students" placeholder="Studenten ausw&auml;hlen" id="aStudents" class="div40a" size="3">
						<datalist id="students">';

            //inserts all possible team members into the datalist
            if ($possibleMembers != NULL) {
                for ($i = 0; $i < count($possibleMembers); $i++) {
                    echo "<option value=" . $possibleMembers[$i]["username"];
                    echo ">";
                    echo $possibleMembers[$i]["firstname"] . " "
                        . $possibleMembers[$i]["lastname"];
                    echo "</option>";
                }
                echo '</datalist>
							<div id="move">
								<input type="button" id="addStudent" class="button_s" value=">">
								<input type="button" id="delStudent" class="button_s" value="<">
							</div>';
            } else {
                echo '<option>Keine verfügbaren Teammitglieder.</option>
							<option>Bitte wenden Sie sich an einen Dozenten.</option>
							</datalist>
							<div id="move">
								<input type="button" id="addStudent" class="button_s" value=">" disabled>
								<input type="button" id="delStudent" class="button_s" value="<" disabled>
							</div>';
            }

            echo '
						
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
	
		if (!isset($_SESSION["permission"]) || $_SESSION["permission"] != 3 ) {
			echo "<br>no Permission to Student";
			return;
		}
		
        //$students = $this->db->getProjectMembers($_SESSION["userID"]);
        $leaid = $this->db->getLeaID($_SESSION["userID"])->LEAID;
        $project = $this->db->getProject($_SESSION["userID"]);
        $students = $this->db->getProjectMembers($_SESSION["userID"], $project->ID);
        $milestones = $this->db->getMilestones($leaid);
        $logbook = $this->db->getLogbook($project->ID);
		$_SESSION["pid"] = $project->ID;


        if (isset($_POST["editProject"])) {

            //validieren auf:
            //-Maximallänge der Eingaben
            //-Minimallänge

            $table = $_POST["sqlTable"];
            $column = $_POST["sqlColumn"];
            $value = $_POST[$column . "Value"];


            //$breaks = array("\n","<br />","<br>","<br/>");
            //$value = str_ireplace($breaks, "\u1000", $value);

            /* $value = nl2br(htmlentities($value, ENT_QUOTES, 'UTF-8')); */
            $this->db->updateSingleValue($table, $column, $value, $project->ID);

            /* echo $project->idea_description;
            die; */

            header('Location: ' . $_SERVER['PHP_SELF'] . '?controller=Student&do=showStudent');
            die;
        }

       

        include 'header.php';


        echo '<div id="team_overview">
				<center><h1>Team verwalten</h1></center>
				<div id="team_div" class="div50a">
					<h2>Team</h2>
					<table>';

        foreach ($students as $row) {
            $student = $this->db->selectUserByID($row->STUDENTID);
            echo '<tr><td>' . $student->firstname . ' ' . $student->lastname . '</td></tr>';
        }

        echo '</table>
				</div>
				<div id="edit_div" class="div50a">
					<h2>&Uuml;bersicht</h2>
					<table>
						<tr>
							<td>Projekt Arbeitstitel
								<input type="button" id="edit__project__title" class="button_s button_edit" value="+"/>
							</td>
							<td>
								<input type="text" id="titleDisplay" value="' . $this->showFirstLine($project->title) . '"disabled/>
							</td>
						</tr>
						<tr>
							<td>Projekt Kurzbeschreibung
								<input type="button" id="edit__project__task" class="button_s button_edit" value="+"/>
							</td>
							<td>
								<input type="text" id="taskDisplay" value="' . $this->showFirstLine($project->task) . '" disabled/>
							</td>
						</tr>
						<tr>
							<td>Ausf&uuml;hrliche Beschreibung
								<input type="button" id="edit__project__idea_description" class="button_s button_edit" value="+"/>
							</td>
							<td>
								<input type="text" id="idea_descriptionDisplay" value="' . $this->showFirstLine($project->idea_description) . '" disabled/>
							</td>
						</tr>
						<form id="editProjectForm" method="post">
					
							<div id="dialogbox"><!---Die zu öffnende box-->
								<div id="dialogboxcontent">
									<label id="popupTitle"></label>
									<span id="close">close</span>
									<textarea id="titleBigText" class="bigTextArea" name="titleValue">' . $project->title . '</textarea>
									<textarea id="taskBigText" class="bigTextArea" name="taskValue">' . $project->task . '</textarea>
									<textarea id="idea_descriptionBigText" class="bigTextArea" name="idea_descriptionValue">' . $project->idea_description . '</textarea>
									<input id="sqlTable" name="sqlTable" type="hidden" value=""/>
									<input id="sqlColumn" name="sqlColumn" type="hidden" value=""/>
									<input class="button" type="submit"  name="editProject" value="Bestätigen"/>
								</div>
							</div>
						</form>
					</table>
				</div>
				<div id="data_div" class="div50a">
					<h2>Abgaben</h2>
				<table>';

				foreach($milestones as $row)
				{
					echo '<tr>
						<td>Beschreibung: '.$row->description.' Abgabe: '.$row->deadline.'&#09;';
					
					if($this->db->checkFile($project->ID,$row->ID)){
						echo'<input type="button" value="&#10004" disabled/>
							<form method="POST" style="display:inline;">
							<input type="hidden" name="ms" value="'.$row->ID.'"
							<input type="hidden" name="controller" value="Student"/>
							<input type="hidden" name="do" value="deleteFile"/>
							<input type="submit" value="&#10008;"/>
							</form>
							</td>
							</tr>';		
					}
					else{
						echo'<form method="POST" enctype="multipart/form-data">
							<input type="hidden" name="ms" value="'.$row->ID.'"
							<input type="hidden" name="controller" value="Student"/>
							<input type="hidden" name="do" value="uploadFile"/>
							<input type="file" name="msFile" value="..."/>
							<input type="submit" value="Upload"/>
							</form>
							</td>
							</tr>';
					}
				}

        echo '</table>
					</div>
					<div id="upload_div" class="div50a">
						<h2>Hochladen</h2>
						<input type="button" class="button_l" value="Hochladen"/>
					</div>
					
					<div id="log_div" class="div100a">
						<h2>Logbuch</h2>
						<input type="search" id="log" list="log_list" placeholder="Eintrag ausw&auml;hlen..."/><br/><br/>
							<datalist id="log_list">';

        foreach ($logbook as $row) {
            echo '<option>' . $row->entry . '</option>';
        }

        echo '</datalist>
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
	
	public function uploadFile()
	{
		$pid = $this->db->getProject($_SESSION["userID"])->ID;
		$this->db->uploadFile($pid, $_POST["ms"]);
	}
	
	public function deleteFile()
	{
		$pid = $this->db->getProject($_SESSION["userID"])->ID;
		$this->db->deleteFile($pid, $_POST["ms"]);
	}

}

?>