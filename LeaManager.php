<?php
namespace LEO;
use LEO\Model\LeaManagerDatabase;
class LeaManager
{
    private $db;
	private $errors = array();
	private $validData = array();
	private $labels = array("from" => "Von", "till" => "Bis" );
	private $labelsMilestone = array("deadline" => "Abgabetermin" , "description" => "Beschreibung");
	
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
							<input type="submit" id="update_lea" name="edit" value="LEA-Bearbeiten" class="button_m">
						</div>
					</form>
					<form method="POST">
						<div class="buttons">
                            <input type="hidden" name="controller" value="LeaManager"/>
							<input type="hidden" name="do" value="showLeaStatus"/>
                            <input type="hidden" id="LeaID" name="LeaID" value="'.$row->ID.'"/>
							<input type="submit" id="update_lea" name="edit" value="LEA Übersicht" class="button_m">	
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
										<input type="hidden" name="do" value="validateCreateLEA"/>
										<input type="submit" class="button_m leasubmit" value="Speichern" id="saveLEA"/>
									</form>
								</div>
                                
                                ';
	}
    
    public function showUpdateLea(){
		
        echo '<script type="text/javascript" src="script/editLEA.js"></script>';
		
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
                                        <input type="hidden" name="do" value="validateAddMilestone"/>
                                        <input type="hidden" name="controller" value="LeaManager"/>
                                        <input class="button" id="milestoneSub" type="submit" value="Best&auml;tigen"/>

                                    </form>
                                </div>
                            ';
	}
    
	public function showLeaStatus(){
        
		if (!isset($_SESSION["permission"]) || $_SESSION["permission"] != 1 ) {
			echo "<br>no Permission to LeaManager";
			return;
		}
		
        $Lea = $this->db->getLeaByID($_POST['LeaID']);
		$Milestones = $this->db->getMilestones($Lea->ID);
		$Projects = $this->db->getProjects($Lea->ID);
		$docCountLea = $this->db->getMilestoneCount($Lea->ID)->c;
		$teamless = $this->db->getTeamless($Lea->ID);
		$totalDocs = count($Projects);
		
		
		include 'header.php';
		
		echo'<form>
			<input type="hidden" name="controller" value="LeaManager"/>
			<input type="hidden" name="do" value="showHome"/>
			<input type="submit" class="button_m floatR" id="showHome" value="Zur&uuml;ck"/>
			</form>
			<div id="lea_overview">
				<div class="lea_title">'
					.$Lea->title.
				'</div>
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
					</tr>';
						
		foreach($Milestones as $row)
		{	
			$enteredDocs = $this->db->getDocsForMilestone($row->ID)->c;
			
			echo'<tr class="ms_table_row">
					<td>'.$row->description.'</td>
					<td>'.$row->deadline.'</td>
					<td>'.$enteredDocs.'/'.$totalDocs.'</td>
				</tr>';
		}
			
		echo'	</table>
				</div>
			
				<div id="bl">
					<table id="teams_table">
						<th>Teams</th>
						<th>Abgaben</th>';
						
		foreach($Projects as $row)	
		{
			$pid = $row->ID;
			$pmembers = $this->db->getProjectMembers($pid);
			$docCountProject = $this->db->getDocumentCount($pid)->c;
			
			echo'<tr class="teams_table_row">
					<td>
						Team &lt;';
						$lastMember = end($pmembers);
						foreach($pmembers as $row)
						{
							if($row != $lastMember && !empty($row))
								echo $row->firstname.' '.$row->lastname.','; 
							else
								echo $row->firstname.' '.$row->lastname;					
						}	
						
			echo		'&gt;
						<input type="submit" name="edit" value="Mail">
						<input type="submit" name="edit" value="Team angucken">
					</td>
					<td>'.
						$docCountProject.'/'.$docCountLea
					.'</td>
				</tr>';
					
		}
		
		echo'   </table>
				</div>
				<div id="br">
					<table id="teamless_table">
					<th>Teamlose Studis</th>';
					
		foreach($teamless as $row)
		{
			echo'<tr class="teamless_table_row">
					<td>'.$row->firstname.' '.$row->lastname.'</td>
				</tr>';
		}
		
		echo'		</table>
				</div>	
			</div>';
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
		$ID = $this->db->writeLEA($this->validData);
		$_POST['LeaID'] = $ID;
		$this->showUpdateLEA();
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
	
	public function sendMailTest() {
		
	$empfaenger = 'nils.niediek@bi.bib.de';
	$betreff = 'Der Betreff';
	$nachricht = 'Hallo';
	$header = 'From: julius.boecker@bi.bib.de' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	mail($empfaenger, $betreff, $nachricht, "From: <julius.boecker@bi.bib.de>");
		echo 'läuft';
	}
	
	public function validateCreateLEA(){
				
		
         foreach ($this->labels as $index => $value) {
			
            if (!isset($_POST[$index]) || empty($_POST[$index])) {
                $this->errors[$index] = "Das " . $value . "-Feld muss bef&uuml;llt sein!<br>";
			}	
			 
				
			else{
			 
				if(isset($_POST['selectedClasses'])){
					// adding selectedClasses array to validData array
					$this->validData['selectedClasses'] = array();
					// adding each selected class to selectedClasses array
					foreach($_POST['selectedClasses'] as $class){
						array_push($this->validData['selectedClasses'] , $class);
					}	
				}
				
			   
                $this->validData[$index] =  filter_input(INPUT_POST, $index, FILTER_DEFAULT);
                
            }
		 }
			
			count($this->errors) > 0 ? $this->showCreateLea() : $this->saveLEA();
		
    }
	
	public function validateAddMilestone(){
		
		 foreach ($this->labelsMilestone as $index => $value) {
			if (!isset($_POST[$index]) || empty($_POST[$index])) {
                $this->errors[$index] = "Das " . $value . "-Feld muss bef&uuml;llt sein!<br>";
			}	
			else{
				$this->validData[$index] =  filter_input(INPUT_POST, $index, FILTER_DEFAULT);
			}	
		 }
		//	$this->validData['LEAID'] =  $_POST['LEAID'];	
			var_dump($this->validData);
		count($this->errors) > 0 ? $this->updateLEA() : $this->addMilestone();
		
	}
}
?>