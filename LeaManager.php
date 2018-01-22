<?php

namespace LEO;

use LEO\Model\Database;

class LeaManager
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function showHome()
    {


        include 'header.php';

        echo '
		
		<div id="overview_panel">
									<p> Übersicht über die LEAs </p>
									</br>
									</br>
									</br>
								<form method="POST">
										<input type="hidden" name="controller" value="LeaManager"/>
										<input type="hidden" name="do" value="showCreateLea"/>
										<input type="submit" id="add_lea" name="add" value="LEA hinzuf&uuml;gen" class="button_100">
								
								</form>
								<div class="LEAcontainer">
								';

        $titles = $this->db->getLEAs();

        foreach ($titles as $key => $val) {
            foreach ($val as $key2 => $val2) {
                echo '
			<div class="LEA">
											<p class="date"> ' . $val2 . '</p>
											<form method="GET">
												
												
												<div class="buttons">
													<input type="submit" name="overview" value="LEA-&Uuml;bersicht" class="button_m"/>
													<input type="hidden" name="title" value="' . $val2 . '""/>
													<input type="hidden" name="do" value="editLEA"/>
													<input type="hidden" name="controller" value="LeaManager"/>
													<input type="submit" name="submit" value="Bearbeiten" class="button_m "/>
													
												</div>
											</form>
										</div>
			
					';

            }
        }

        echo '						
									</div>
								</div>
							</div>
							
			</div>
			
							
						</body>
						
					</html>
		';


    }

    public function showCreateLea()
    {

        $res = $this->db->getClasses();

        include 'header.php';

        echo '
							
								<a href="?do=leamanager"><input type="button" class="button_m floatR" value="Zur&uuml;ck"/></a>
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
												<select id="aClasses" class="LeaFormLeft" size="3" multiple="multiple">';
        foreach ($res as $row) {
            echo '
                                                            <option :selected>' . $row->name . '</option>
                                                       ';
        }

        echo '                                  </select>
                                                <div id="classButtons" class="floatL">
                                                    <input type="button" id="pickClass" class="button_100 floatL" value="Klasse auswählen"/>
                                                    <input type="button" id="delClass" class="button_100 floatL" value="Klasse Löschen" style="display: none;"/>
                                                </div>
												<select name="selectedClasses[ ]" id="rClasses" class="LeaFormRight" size="3" multiple="multiple">
												
												</select>
										</div>
										
										<div id="time">
											<div class="div40 floatL">
												Von:<br/><input type="date" id="from" name="from"/>
											</div>
											<div class="div40 floatR">
												Bis:<br/><input type="date" id="till" name="till"/>
											</div>
										</div>
										
					
										<input type="hidden" name="controller" value="LeaManager"/>
										<input type="hidden" name="do" value="saveLEA"/>
										<input type="submit" class="button_m" value="Speichern" id="saveLEA"/>
									</form>
									
								</div>
                                
							</div>
						</body>
						
					</html>';
    }

    public function editLEA()
    {
        $title = $_GET['title'];
        //echo "the title is " .$title; nothing in $_POST['title']
        $resTitles = $this->db->selectLEAByTitle($title);
        $resClasses = $this->db->getClasses();

        $today = date('Y-m-d');

        //getting the MileStone titles from the Database
        $milestoneDescriptions = $this->db->getMilestoneDescription($title);

        //getting the LEAID
        $leaID = $this->db->getLEAIDByTitle($title);


        include 'header.php';


        //<script type="text/javascript" src="\js\jquery-3.1.1.min.js"></script>
        //<script type="text/javascript" src="\js\jquery-ui-1.10.3.custom\development-bundle\ui\jquery.ui.datepicker.js"></script>


        //alert("test");


        /*
        var temp = document.getElementById("milestoneSub");
        temp.onclick=function(){

            document.getElementById("description").style.background = "RED";
            document.getElementById("description").placeholer="BLA";
            return false;

        };
        */
        echo '
		<script type="text/javascript">
		$(document).ready(function() {
		
		var modal = document.getElementById("dialogbox2");
		modal.style.display = "none";
		//Get the button that opens the modal
		var btn = document.getElementById("AddMilestone");

		// Get the <span> element that closes the modal
		var span = document.getElementsByClassName("close2")[0];

		// When the user clicks on the button, open the modal 

		btn.onclick = function() {
			modal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
			modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}
		
			var sub = document.getElementById("milestoneSub");
			var input = document.getElementById("description");
			var deadline = document.getElementById("deadline");
			
			sub.onclick = function(){
				
			if(description.value.length > 0 && deadline.value.length > 0){
				document.getElementById("deadline").style.border = "1px solid black";
				document.getElementById("description").style.border = "1px solid black";
			return true;
			}
			else{
				if(description.value.length == 0)
				{	
					if(deadline.value != ""){
					document.getElementById("deadline").style.border = "1px solid black";
					}
				document.getElementById("description").style.border = "1px solid red";
				document.getElementById("description").placeholder="Bitte geben Sie eine Meilensteinbeschreibung ein!";
				return false;
				}
				else{
					if(deadline.value == ""){
						if(description.value != 0){
						document.getElementById("description").style.border = "1px solid red";
						}
						
					document.getElementById("description").style.border = "1px solid black";	
					document.getElementById("deadline").style.border = "1px solid red";
					return false;
				}
				}
			}
			}
		
		
	});
	</script>
				<div id="dialogbox2"><!---Die zu öffnende box-->
			
					<div id="dialogboxcontent2">
						<form Method="POST">
							<span class="close2">close</span>
							<!--leaID-->
							<input type="hidden" name="LEAID" value="' . $leaID[0]['ID'] . '"/>
							<!--<label for="title">Titel:</label>
							<input id="milestoneTitle" name="title">-->
							
							<label id="deadlineId" for="deadline">Abgabetermin:</label>
							<input type="date" id="deadline" name="deadline" min="' . $today . '">
							
							<label for="description">Beschreibung:</label>
							<textarea id="description" name="description" placeholder=""></textarea>
							
							
							<input type="hidden" name="do" value="addMilestone"/>
							<input type="hidden" name="controller" value="LeaManager"/>
							<input class="button"id="milestoneSub" type="submit" value="Bestätigen"/>
						
						</form>
					</div>
					</div>
					<!--end dialogbox2-->
								<a href="?do=showHome&controller=LeaManager"><input type="button" class="button_m floatR" value="Zur&uuml;ck"/></a>
								
								<div id="addLeaDiv">			
								<div><h2 id="title">' . $resTitles[0]['title'] . '</h2></div>
									<form id="addLeaForm" method="POST">
										
										<div id="available">
										Verf&uuml;gbare Klassen:					
										</div>
										<div id="selected">
											Ausgew&auml;hlte Klassen:
										</div>
										<div id="classes">
											<select id="aClasses" class="LeaFormLeft" size="3">';

        foreach ($resClasses as $row) {
            echo '
                                                            <option :selected>' . $row->name . '</option>
                                                       ';
        }
        echo '								</select>	
											<div id="classButtons" class="floatL">
                                                    <input type="button" id="pickClass" class="button_100 floatL" value="Klasse auswählen"/>
                                                    <input type="button" id="delClass" class="button_100 floatL" value="Klasse Löschen" style="display: none;"/>
                                             </div>
												<select name="selectedClasses[ ]" id="rClasses" class="LeaFormRight" size="3" multiple="multiple">
												
												</select>	
											</select>
											
										</div>
										
										<!--
										<div id="time">
											<div class="div40 floatL">
												Von:<br/><input type="date" id="from" name="from"/>
											</div>
											<div class="div40 floatR">
												Bis:<br/><input type="date" id="till" name="till"/>
											</div>
										</div>-->
										
										<div id="milestones">
											<div id="ms_left">
												<select id="ms_list" class="LeaFormLeft" size="3">';
        foreach ($milestoneDescriptions as $key => $val) {
            foreach ($val as $key2 => $val2) {
                echo "<option>" . $val2 . "</option>";


            }
        }
        echo '													
												</select>
											</div>
											<div id="ms_right">
												<input type="button" id="AddMilestone" class="button_100_100" value="Meilenstein hinzuf&uuml;gen"/>
											</div>
										</div>
										<!-- <input type="hidden" name="controller" value="LeaManager"/>
										<input type="hidden" name="do" value="insertLEAData"/>
										<input type="submit" class="button_m" value="Speichern???" id="saveLEA"/> -->
									</form>
									
								</div>
							
				
			</div> <!--wrapper-->
						</body>
						
					</html>';


    }

    public function saveLEA()
    {
        $this->db->writeLEA($_POST);
        $this->showHome();
    }

    public function addMilestone()
    {
        $this->db->addMilestone($_POST);
        $this->editLEA();
    }

    public function validateForm()
    {
        foreach ($this->labels as $index => $value) {
            if (!isset($_POST[$index]) || empty($_POST[$index])) {
                $this->errors[$index] = "Das " . $value . "-Feld muss bef&uuml;llt sein!<br>";
            } else if ($index == "entry" && strlen($_POST[$index]) <= 6) {
                $this->errors[$index] = "Kommentar muss l&auml;nger als 6 Zeichen sein!<br>";
            } else if ($index == "iName" && !preg_match('/^.*jpg$/', $_POST[$index])) {
                $this->errors[$index] = "Dateiformat muss .jpg sein!<br>";
            } else {
                $this->validData[$index] = filter_input(INPUT_POST, $index, FILTER_DEFAULT);
            }
        }
        count($this->errors) > 0 ? $this->showArticleForm() : $this->writeArticleContent($this->validData);
    }


}

?>
