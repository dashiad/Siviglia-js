<div class="SivForm WebUser">
    <div class="SivFormHeader">
        <h2>EditAction</h2>
        <div class="SivFormDescription">
            
        </div>
    </div>
    <div class="SivFormBody">
         <form method="post" data-dojo-type="dijit/form/Form" data-dojo-attach-point='mainForm'>
            <div class="SivFormContents">
                <div class="SivFieldContainer">
                    <div class="SivFieldContainerTitle"></div>
                    <div class="SivFieldContainerDescription"></div>                
                    <table cellpadding=0 cellspacing=0>
                        <tbody data-dojo-attach-point='tableNode'>
						<tr>
							<td class="SivFormLabel SivFormRequiredField">LOGIN</td>
							<td class="SivFormInput SivFormRequiredField">
				<input name="LOGIN" data-dojo-type="Siviglia/forms/inputs/Login" data-dojo-attach-point="LOGIN"  data-dojo-definition='{"MINLENGTH":4,"MAXLENGTH":15,"REGEXP":"\/^[a-z\\d_]{3,15}$\/i","ALLOWHTML":false,"TRIM":true,"REQUIRED":true}'>
							</td>
						</tr>
						<tr>
							<td class="SivFormLabel SivFormRequiredField">PASSWORD</td>
							<td class="SivFormInput SivFormRequiredField">
				<input name="PASSWORD" data-dojo-type="Siviglia/forms/inputs/Password" data-dojo-attach-point="PASSWORD"  data-dojo-definition='{"MINLENGTH":6,"MAXLENGTH":16,"REGEXP":"\/^[a-z\\d_]{6,16}$\/i","TRIM":true,"REQUIRED":true}'>
							</td>
						</tr>
						<tr>
							<td class="SivFormLabel SivFormRequiredField">EMAIL</td>
							<td class="SivFormInput SivFormRequiredField">
				<input name="EMAIL" data-dojo-type="Siviglia/forms/inputs/Email" data-dojo-attach-point="EMAIL"  data-dojo-definition='{"MINLENGTH":8,"MAXLENGTH":50,"REGEXP":"\/^[^@]+@[a-zA-Z0-9._-]+\\.[a-zA-Z]+$\/","ALLOWHTML":false,"TRIM":true,"REQUIRED":true}'>
							</td>
						</tr>
						<tr>
							<td class="SivFormLabel SivFormRequiredField">EXTTYPE</td>
							<td class="SivFormInput SivFormRequiredField">
				<input name="EXTTYPE" data-dojo-type="Siviglia/forms/inputs/Integer" data-dojo-attach-point="EXTTYPE"  data-dojo-definition='{"DIGITS":4,"DEFAULT":0,"REQUIRED":true}'>
							</td>
						</tr>
						<tr>
							<td class="SivFormLabel SivFormRequiredField">EXTID</td>
							<td class="SivFormInput SivFormRequiredField">
				<input name="EXTID" data-dojo-type="Siviglia/forms/inputs/String" data-dojo-attach-point="EXTID"  data-dojo-definition='{"MAXLENGTH":100,"DEFAULT":0,"REQUIRED":true}'>
							</td>
						</tr>
						<tr>
							<td class="SivFormLabel SivFormRequiredField">NLOGINS</td>
							<td class="SivFormInput SivFormRequiredField">
				<input name="NLOGINS" data-dojo-type="Siviglia/forms/inputs/Integer" data-dojo-attach-point="NLOGINS"  data-dojo-definition='{"DIGITS":4,"DEFAULT":0,"REQUIRED":true}'>
							</td>
						</tr>
						<tr>
							<td class="SivFormLabel SivFormRequiredField">LASTLOGIN</td>
							<td class="SivFormInput SivFormRequiredField">
				<input name="LASTLOGIN" data-dojo-type="Siviglia/forms/inputs/DateTime" data-dojo-attach-point="LASTLOGIN"  data-dojo-definition='{"REQUIRED":true}'>
							</td>
						</tr>
						<tr>
							<td class="SivFormLabel SivFormRequiredField">LASTIP</td>
							<td class="SivFormInput SivFormRequiredField">
				<input name="LASTIP" data-dojo-type="Siviglia/forms/inputs/IP" data-dojo-attach-point="LASTIP"  data-dojo-definition='{"MAXLENGTH":15,"REQUIRED":true}'>
							</td>
						</tr>
						<tr>
							<td class="SivFormLabel SivFormRequiredField">STATE</td>
							<td class="SivFormInput SivFormRequiredField">
				<input name="STATE" data-dojo-type="Siviglia/forms/inputs/State" data-dojo-attach-point="STATE"  data-dojo-definition='{"VALUES":["ACTIVE","FROZEN"],"DEFAULT":"ACTIVE","REQUIRED":true}'>
							</td>
						</tr>
						<tr>
							<td class="SivFormLabel SivFormRequiredField">FAILEDLOGINATTEMPTS</td>
							<td class="SivFormInput SivFormRequiredField">
				<input name="FAILEDLOGINATTEMPTS" data-dojo-type="Siviglia/forms/inputs/Integer" data-dojo-attach-point="FAILEDLOGINATTEMPTS"  data-dojo-definition='{"DIGITS":4,"DEFAULT":0,"REQUIRED":true}'>
							</td>
						</tr>
						<tr>
							<td class="SivFormLabel SivFormRequiredField">VALIDATED</td>
							<td class="SivFormInput SivFormRequiredField">
				<input name="VALIDATED" data-dojo-type="Siviglia/forms/inputs/Boolean" data-dojo-attach-point="VALIDATED"  data-dojo-definition='{"DEFAULT":1,"REQUIRED":true}'>
							</td>
						</tr>
						<tr>
							<td class="SivFormLabel">PHOTO</td>
							<td class="SivFormInput">
				<input name="PHOTO" data-dojo-type="Siviglia/forms/inputs/File" data-dojo-attach-point="PHOTO"  data-dojo-definition='{"TARGET_FILEPATH":"c:\/xampp\/htdocs\/framework\/images\/{%@currentModel\/LOGIN%}","TARGET_FILENAME":"{%@currentModel\/USER_ID%}-{%@currentModel\/MyUser\/CUSTOM1%}"}'>
							</td>
						</tr>
                 </tbody>            
            </table>
            </div>
            <div class="SivFormButtons"></div>
        </div>
    </form>
</div>