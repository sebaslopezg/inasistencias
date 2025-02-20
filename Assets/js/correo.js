if (document.querySelector("#frmCorreo")) {
  let frmCorreo = document.querySelector("#frmCorreo");

  frmCorreo.addEventListener("submit", (e) => {
    e.preventDefault();

    let txtCorreo = document.querySelector("#txtCorreo").value;

    if (txtCorreo == "") {
      Swal.fire({
        title: "Ingresar",
        text: "Por favor, escribe tu correo",
        icon: "error"
      });
      return false;
    } else {
      frmData = new FormData(frmCorreo);
      fetch(base_url + "/correo/sendEmail", {
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

}
