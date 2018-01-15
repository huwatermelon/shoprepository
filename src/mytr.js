var curtab = null;
function do_onclick(tab){
  if(curtab != null) curtab.style.backgroundColor = "white"; 
  tab.style.backgroundColor = "yellow";
  curtab = tab;
}
