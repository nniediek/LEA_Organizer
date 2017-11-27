<?php 
	class Leamanager {
	
		public function showHome() {
		
			include 'header.php';
			
			echo'
			<div id="overview_panel">
				<p> Übersicht über die LEAs </p>
				</br>
				</br>
				</br>
				<a href="?do=leahinzufuegen" id="add_lea" name="add" value="" class="button_100"> LEA hinzuf&uuml;gen </a>
				<div class="LEAcontainer">
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
		';
		}
		
		
		public function showLeaHinzufuegen() {
			echo' <p>Eingeloggt als:</p>
			<div class="username">
				&lt;Hänsch&gt;
			</div>
			</br>
			<input type="button" id="logout" class="button_m" value="Logout">
			';
		}
		
		public function showSite3() {
			echo '<p>
                    <h1> site 3 </h1>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Curabitur pretium tincidunt lacus. Nulla gravida orci a odio. 
                  </p>';
		}
		
		public function error() {
			echo 'SOMETHING WENT WRONG';
		}
		
	}
?>
