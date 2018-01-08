<?php
	
class LEAmanager extends User{

	// constructor of the lea manager
	public function __construct($username, $firstName, $lastName, $eMail){
		parent::__construct($username, $firstName, $lastName, $eMail);
	}

	/* ------------------------------------------------------------------------------------ */
	
public function showHome(){
		
		$res = $this->db->getLEAs();
		$leaCount = 0;
		include 'header.php';		
		
		echo'
			<div id="overview_panel">
				<p> Übersicht über die LEAs </p>
				</br>
				</br>
				</br>
					<form method="POST">
							<input type="hidden" name="do" value="addLEA"/>
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
										<input type="hidden" name="do" value="insertLEAData"/>
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
	
	public function showLeaStatus (){
		
		include 'header.php';
		
		echo' 					<div id="lea_overview">
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
	
	
		

	
	public function error() {
		echo 'SOMETHING WENT WRONG';
	}
}
?>