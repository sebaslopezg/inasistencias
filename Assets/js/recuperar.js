const params = new URLSearchParams(window.location.search);
const codigo = params.get("codigo");
const correo = params.get("correo");

fetch(base_url + "/recuperar/verificarToken?codigo=" + codigo + "&correo=" + correo)
  .then((res) => res.json())
  .then((data) => {
    if (data.status) {
      document.querySelector("#card-body").innerHTML = `${data.action}`;
      document.querySelector("#txtCorreo").value = correo;
      document.querySelector("#txtCodigo").value = codigo;

      if (document.querySelector("#frmChangePass")) {
        let frmChangePass = document.querySelector("#frmChangePass");
        frmChangePass.addEventListener("submit", (e) => {
          e.preventDefault();

          let nueva_contraseña = document.querySelector("#nueva_contraseña").value;
          if (nueva_contraseña === "") {
            Swal.fire({
              title: "Ingresar",
              text: "Por favor, escribe tu nueva contraseña",
              icon: "error"
            });
            return;
          }

          const frmData = new FormData(frmChangePass);
          fetch(base_url + "/recuperar/changePass", {
            method: "POST",
            body: frmData
          }).then((res) => {
            if (res.status === 200) {
              res.json().then((data) => {
                if (data.status) {
                  Swal.fire({
                    title: "Correcto",
                    text: data.msg,
                    icon: "success"
                  });
                  window.location = base_url + "/login";
                } else {
                  Swal.fire({
                    title: "Error",
                    text: data.msg,
                    icon: "error"
                  });
                }
              });
            } else {
              Swal.fire({
                title: "Atención",
                text: "Error en el proceso, intente más tarde",
                icon: "error"
              });
            }
          });
        });
      }
    } else {
      document.querySelector("#container").innerHTML = `${data.action}`;
    }
  })
  .catch((error) => {
    console.error("Error en la verificación del token:", error);
    Swal.fire({
      title: "Error",
      text: "Ocurrió un error en la verificación del token, intente más tarde.",
      icon: "error"
    });
  });
