<?php
namespace LEO;
use LEO\Model\LeaManagerDatabase;
class LeaManager
{
    private $db;
    public function __construct()
    {
		if (!isset($_SESSION["permission"]) || $_SESSION["permission"] != 1 ) {
			return;
		}
        $this->db = new LeaManagerDatabase();
    }
	
	public function showHome(){
		
		if (!isset($_SESSION["permission"]) || $_SESSION["permission"] != 1 ) {
			echo "<br>no Permission to LeaManager";
			return;
		}
		$res = $this->db->getLEAs();
        
		include 'header.php';		
		
		echo'
			<div id="overview_panel">
				<p> &Uuml;bersicht &uuml;ber die LEAs </p>
				</br>
				</br>
				</br>
					<form method="POST">
							<input type="hidden" name="controller" value="LeaManager"/>
							<input type="hidden" name="do" value="showCreateLea"/>
							<input type="submit" id="add_lea" name="add" value="LEA hinzuf&uuml;gen" class="button_100">
					
					</form>
						<div class="LEAcontainer">';
						
		foreach($res as $row)
		{
			
			echo'
				<div class="LEA">
					<form method="POST">
						<p>'.$row->title.'</p>
						<p class="date">&lt;'.$row->startdate.' - '.$row->enddate.'&gt;</p>
						<div class="buttons">
                            <input type="hidden" name="controller" value="LeaManager"/>
							<input type="hidden" name="do" value="showUpdateLea"/>
                            <input type="hidden" id="LeaID" name="LeaID" value="'.$row->ID.'"/>
							<input type="submit" id="update_lea" name="edit" value="LEA-&Uuml;bersicht" class="button_m">
						</div>
					</form>
				</div>';
		}
		
		echo'</div>
			 </div>
			 ';
	}
	
	public function showCreateLea(){
    
	if (!isset($_SESSION["permission"]) || $_SESSION["permission"] != 1 ) {
			echo "<br>no Permission to LeaManager";
			return;
		}
		
        $res = $this->db->getClasses();
		
		include 'header.php';
		echo'                    
                                <form>
                                <input type="hidden" name="controller" value="LeaManager"/>
                                <input type="hidden" name="do" value="showHome"/>
								<input type="submit" class="button_m floatR" id="showHome" value="Zur&uuml;ck"/>
                                </form>
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
                                                    foreach($res as $row) 
                                                    {
                                                       echo'
                                                            <option :selected>'.$row->name.'</option>
                                                       '; 
                                                    }
                                                        
        echo'                                  </select>
                                                <div id="classButtons" class="floatL">
                                                    <input type="button" id="pickClass" class="button_100 floatL" value="Klasse ausw&auml;hlen"/>
                                                    <input type="button" id="delClass" class="button_100 floatL" value="Klasse L&ouml;schen" style="display: none;"/>
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
										
										<div id="milestones">
											<div id="ms_left">
												<select id="ms_list" class="LeaFormLeft" size="3">';
                                                
        echo'
                                                
												</select>
											</div>
											<div id="ms_right">
												<input type="button" id="AddMilestone" class="button_100_100 leasubmit" value="Lea erstellen um Meilensteine hinzuzuf&uuml;gen!" disabled/>
											</div>
										</div>
										<input type="hidden" name="controller" value="LeaManager"/>
										<input type="hidden" name="do" value="saveLEA"/>
										<input type="submit" class="button_m leasubmit" value="Speichern" id="saveLEA"/>
									</form>
								</div>
                                
                                ';
	}
    
    public function showUpdateLea(){
        
		if (!isset($_SESSION["permission"]) || $_SESSION["permission"] != 1 ) {
			echo "<br>no Permission to LeaManager";
			return;
		}
		
        $Lea = $this->db->getLeaByID($_POST['LeaID']);
        $classesTotal = $this->db->getClasses();
        $classesSelected = $this->db->getClassesByLeaID($Lea->ID);
        $Milestones = $this->db->getMilestones($Lea->ID);
		$today = date("d-m-Y");
        echo $today;
        
        include 'header.php';
		echo'                 
							
								<form>
                                <input type="hidden" name="controller" value="LeaManager"/>
                                <input type="hidden" name="do" value="showHome"/>
								<input type="submit" class="button_m floatR" id="showHome" value="Zur&uuml;ck"/>
                                </form>
								<div id="addLeaDiv">			
									<form id="addLeaForm" method="POST">
										<h1 id="title">'.$Lea->title.'</h1>
										<div id="available">
										Verf&uuml;gbare Klassen:					
										</div>
										<div id="selected">
											Ausgew&auml;hlte Klassen:
										</div>
										<div id="classes">
												<select id="aClasses" class="LeaFormLeft" size="3" multiple="multiple">';
                                                    foreach($classesTotal as $row) 
                                                    {
                                                        $count=0;
                                                        foreach($classesSelected as $row2){
                                                            if($row->ID == $row2->ID){
                                                                $count++;
                                                            }
                                                        }
                                                        if($count == 0){
                                                            echo '<option>'.$row->name.'</option>';
                                                        }
                                                    }
                                                        
        echo'                                  </select>
                                                <div id="classButtons" class="floatL">
                                                    <input type="button" id="pickClass" class="button_100 floatL" value="Klasse ausw&auml;hlen"/>
                                                    <input type="button" id="delClass" class="button_100 floatL" value="Klasse L&ouml;schen" style="display: none;"/>
                                                </div>
												<select name="selectedClasses[ ]" id="rClasses" class="LeaFormRight" size="3" multiple="multiple">';
                                                foreach($classesSelected as $row2)
                                                {
                                                    echo'
                                                        <option>'.$row2->name.'</option>
                                                    ';
                                                }
        echo'                                       
												
												</select>
										</div>
										
										<div id="time">
											<div class="div40 floatL">
												Von:<br/><input type="date" id="from" name="from" value="'.$Lea->startdate.'"/>
											</div>
											<div class="div40 floatR">
												Bis:<br/><input type="date" id="till" name="till" value="'.$Lea->enddate.'"/>
											</div>
										</div>
										
										<div id="milestones">
											<div id="ms_left">
                                            <select id="ms_list" class="LeaFormLeft" size="3">';
                                            foreach($Milestones as $row){
                                                echo'<option>'.$row->description.'</option>';
                                            }
        echo'                               

                                            </select>
											</div>
											<div id="ms_right">
												<input type="button" id="AddMilestone" class="button_100_100 leasubmit" value="Meilenstein hinzuf&uuml;gen"/>
											</div>
										</div>
										<input type="hidden" name="controller" value="LeaManager"/>
										<input type="hidden" name="do" value="updateLEA"/>
                                        <input type="hidden" name="LeaID" value="'.$Lea->ID.'"/>
										<input type="submit" class="button_m leasubmit" value="Aktualisieren" id="saveLEA"/>
									
									
								</div>
                                
							</div>

                            <div id="dialogbox2">	
                                <div id="dialogboxcontent2">
                                    
                                        <span class="close2">close</span>

                                        <label id="deadlineId" for="deadline">Abgabetermin:</label>
                                        <input type="date" id="deadline" name="deadline" min="'.$today.'">

                                        <label for="description">Beschreibung:</label>
                                        <textarea id="description" name="description" placeholder=""></textarea>


                                        <input type="hidden" name="LeaID" value="'.$Lea->ID.'"/>
                                        <input type="hidden" name="do" value="addMilestone"/>
                                        <input type="hidden" name="controller" value="LeaManager"/>
                                        <input class="button" id="milestoneSub" type="submit" value="Best&auml;tigen"/>

                                    </form>
                                </div>
                            ';
	}
    
    public function updateLEA(){
		if (!isset($_SESSION["permission"]) || $_SESSION["permission"] != 1 ) {
			echo "<br>no Permission to LeaManager";
			return;
		}
        $this->db->updateLEA($_POST);
        $this->showHome();
    }
	
    public function saveLEA(){  
		if (!isset($_SESSION["permission"]) || $_SESSION["permission"] != 1 ) {
			echo "<br>no Permission to LeaManager";
			return;
		}	
		$this->db->writeLEA($_POST);
		$this->showHome();
	}
    
    public function addMilestone(){
		if (!isset($_SESSION["permission"]) || $_SESSION["permission"] != 1 ) {
			echo "<br>no Permission to LeaManager";
			return;
		}
        $this->db->updateLEA($_POST);
        $this->db->writeMileStone($_POST);
        $this->showUpdateLea();
    }
	
	public function validateForm(){
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