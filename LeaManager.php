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
	
	public function showHome(){
		
		$res = $this->db->getLEAs_pdo();
		$leaCount = 0;
		include 'header.php';		
		
		echo'
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
						<div class="LEAcontainer">';
						
		foreach($res as $row)
		{
			//$this->leas['LEA'.$leaCount] =  $row->ID;
			//$leaCount++;
			
			echo'
				<div class="LEA">
					<form method="POST">
						<p>'.$row->title.'</p>
						<p class="date">&lt;'.$row->startdate.' - '.$row->enddate.'&gt;</p>
						<div class="buttons">
							<input type="submit" name="overview" value="LEA-&Uuml;bersicht" class="button_m">
							<input type="submit" name="edit" value="LEA bearbeiten" class="button_m">
						</div>
					</form>
				</div>';
		}
		
		echo'</div>
			 </div>
			 </div>
			 </body>			
			 </html>';
			 
		//echo var_dump($leas);
	}
	
	public function showCreateLea(){
    
        $res = $this->db->getClasses();
		
		include 'header.php';
		
		echo'
							
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
                                                    foreach($res as $row) 
                                                    {
                                                       echo'
                                                            <option :selected>'.$row->name.'</option>
                                                       '; 
                                                    }
                                                        
        echo'                                  </select>
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
										<input type="hidden" name="controller" value="LeaManager"/>
										<input type="hidden" name="do" value="saveLEA"/>
										<input type="submit" class="button_m" value="Speichern" id="saveLEA"/>
									</form>
									
								</div>
                                
							</div>
						</body>
						
					</html>';
	}
	
	 public function saveLEA(){   
		$this->db->writeLEA($_POST);
		$this->showHome();
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
