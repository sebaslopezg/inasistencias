const params = new URLSearchParams(window.location.search);
const codigo = params.get("codigo");
const correo = params.get("correo");

fetch(base_url + "/recuperar/verificarToken?codigo=" + codigo + "&correo=" + correo)
  .then((res) => res.json())
  .then((data) => {
    if (data.status) {
      document.querySelector("#card-body").innerHTML = `${data.action}`;
    } else {
      Swal.fire({
        title: data.status ? "Correcto" : "Error",
        text: data.msg,
        icon: data.status ? "success" : "error"
      });
    }
  });

/*   if (document.querySelector("#frmChangePass")) {
let frmChangePass = document.querySelector("#frmChangePass");
frmChangePass.addEventListener("submit", (e) => {
  let nueva_contraseña = document.querySelector("#nueva_contraseña").value;

  if (nueva_contraseña == "") {
    Swal.fire({
      title: "Ingresar",
      text: "Por favor, escribe tu nueva contraseña",
      icon: "error"
    });
    return false;
  } else {
    frmData = new FormData(frmChangePass);
    fetch(base_url + "/correo/changePass", {
      method: "POST",
      body: frmData
    }).then((res) => {
      if (res.status == 200) {
        res.json().then((data) => {
          if (data.status) {
            Swal.fire({
              title: "Correcto",
              text: data.msg,
              icon: "success"
            });
          } else {
            Swal.fire({
              title: data.status ? "Correcto" : "Error",
              text: data.msg,
              icon: data.status ? "success" : "error"
            });
          }
        });
      } else {
        Swal.fire({
          title: "Atencion",
          text: "Error en el proceso, intente mas tarde",
          icon: "error"
        });
      }
    });
  }
});
} */
