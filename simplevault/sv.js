function check_pf()
{
  if (document.editentry.pf.value == ""){
    return confirm("You have set an empty passphrase. Do you want to continue?");
  }
  else{
    return true;
  }
}

function toggle_pf(id)
{
  var element = document.getElementById (id);
  element.type = element.type === "password" ? "text" : "password";
}

function generate_password(id, quality)
{
  switch (quality)
  {
  case 'c':
    var chars = "abcdefghijmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWYXZ";
    break
  case 's':
    var chars = "!%=#-?;,:.+_abcdefghijmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWYXZ";
    break
  default:
    var chars = "\\|!\"£$%&/()(=#-?^[];,:.<>+*/@abcdefghijmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWYXZ";
    break
  }
  var len = 16;

  var element = document.getElementById (id);
  element.value = '';

  for (var i = 0; i < len; i++) {
    element.value += chars.charAt(Math.floor(Math.random() * chars.length));
  }
}
