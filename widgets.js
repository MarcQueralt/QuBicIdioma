/* 
 * Invoca la funcionalitat de selectmenu
 */
var $jquery=jQuery.noConflict();
$jquery(document).ready(function(){
    $jquery("#QuBic_Idioma_selector").selectmenu({
        style: 'dropdown',
        menuWidth: 150,
        width: 150
    });
}
);
function QuBic_Idioma_selectorOnChange($desti) {
    if($desti!='#') {
        location=$desti;
    }
}
