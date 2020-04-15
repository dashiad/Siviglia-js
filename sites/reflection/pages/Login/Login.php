[@DEPENDENCY]
    [_BUNDLE]Page[#]
    [_CONTENTS]
        [_CSS]
            [_CODE]
<style type="text/css">
.loginPage .formTitle{

}
.loginPage .siteTitle {
color: #EEE;
font-size: 132px;
margin-top: 20px;
margin-left: 20px;
margin-bottom: 5px;
}
.loginPage .siteSubtitle {
color: #BBB;
font-size: 24px;
margin-top: -29px;
margin-left: 26px;
    margin-bottom:40px;
}
.loginPage .loginForm {
margin-left: 430px;
border: 4px solid var(--secondary-background);
    width:600px;

}
.loginPage .inputContainer {
    padding-bottom: 7px;
    clear: both;
    margin-left: 98px;
    margin-top: 10px;
}
.loginPage .inputLabel {
    font-size: 25px;
    color: var(--main-color);
    float: left;
    width: 150px;
}
.loginPage .formContainer {

}
.loginPage .inputElement {}
.loginPage .formButtonsContainer {
    clear: both;
    background-color: var(--contrast-1);
    height: 40px;
    text-align: right;
    padding: 4px;
    margin-top:25px;
}
.loginPage input {

}
.loginPage input[type=text],.loginPage input[type=password] {
    font-size: 24px !important;
    width:240px !important;
}
.loginPage input[type=password] {
    height:30px !important;
}
.loginPage .formTitle {
    background-color: var(--contrast-0);
    padding: 10px;
    font-size: 28px;
    border: 1px solid var(--main-background);
    color: var(--main-color);
}
.loginPage .formSubmit {
    border: 1px solid var(--secondary-background);
    font-size: 20px;
    padding: 7px;
    background-color: var(--contrast-0);
    color: white;
    width:112px;
}
</style>
            [#]
        [#]
    [#]
[#]
[*PAGE/PAGE]
    [_BODYCLASSES]loginPage[#]
    [_CONTENT]
        <div class="siteTitle">Reflection</div>
        <div class="siteSubtitle">Adtopy project</div>
        <div class="loginForm">
            <div class="formTitle">Acceso</div>
            <div class="formContainer">
            <div data-sivView="Siviglia.model.web.WebUser.forms.Login"></div>
            </div>
        </div>

    [#]
[#]