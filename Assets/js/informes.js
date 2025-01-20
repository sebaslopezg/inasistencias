let tablaInfoAprendiz = document.querySelector("#tabla-aprendices");

let tableVisibility = document.querySelector("#tabla-informe");

document.addEventListener("click", (e) => {
  try {
    let action = e.target.closest("button").getAttribute("data-action");
    let id = e.target.closest("button").getAttribute("data-id");

    if (action == "info") {
      
     /*  fetch(base_url + "/fichas/getFichaById/" + id)
        .then((res) => res.json())
        .then((data) => {
          if (data.status) {
            data = data.data;

            tituloModalFicha.innerHTML = `<h2 class="modal-title fs-5" id="tituloModalFicha" style="text-align: center; display: flex;">MODIFICAR FICHA </h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: white;"> <i class="bi bi-x-lg"></i> </button>
            
            `;
            $("#modalInfo").modal("show");
          } else {
            Swal.fire({
              title: "Error",
              text: data.msg,
              icon: "error"
            });
          }
        }); */
        $("#modalInfo").modal("show");
    }
  } catch {}
});
$(document).ready(function () {
  // -----------------------------------
  //    VERIFICAR Fichas DISPONIBLES
  // -----------------------------------
  let availableFichas = [];
  fetch(base_url + "/informes/getFichas")
    .then((res) => res.json())
    .then((data) => {
      data.forEach((data) => {
        let fila = {
          label: "" + data.nombre_ficha + " - " + data.numeroFicha,
          value: "" + data.id + "",
          numeroFicha: "" + data.numeroFicha + "",
          nombreFicha: "" + data.nombre_ficha + ""
        };
        availableFichas.push(fila);
      });
    });

  console.log(availableFichas);
  // -----------------------------------
  //    AUTOCOMPLETADO DE FICHAS
  // -----------------------------------

  $("#ficha").autocomplete({
    // Establece como fuente de sugerencias el array "availableFichas", que contiene productos disponibles
    source: availableFichas,
    // event: Representa el evento JavaScript que desencadena la función select.
    // ui: Es un objeto de jQuery UI que contiene información específica sobre el elemento seleccionado en la lista de autocompletado.
    // Define la función que se ejecuta al seleccionar un producto de la lista de autocompletado
    select: function (event, ui) {
      // Pasa el idFicha (ui.item.value), el nombre(ui.item.label), el numeroFicha (ui.item.precio), como argumentos.
      agregarFicha(ui.item.value, ui.item.label, ui.item.numeroFicha, ui.item.nombreFicha);
      // Limpia el campo de entrada después de que se haya seleccionado un Ficha
      $("#ficha").val("");
      return false;
    }
  });

  function agregarFicha(idFicha, label, numeroFicha, nombreFicha) {
    // Verificar que solo haya eligido 1 Ficha, para evitar Cruce de Informacion.
    if ($("#tabla-infoFicha tbody tr").length < 1) {
      tableVisibility.style.display = "block";
      // Verificar si la Ficha ya está en la tabla
      let fichaYaAgregado = false;
      // Itera sobre cada fila de la tabla para comprobar si el ID del ficha ya existe
      $("#tabla-infoFicha tbody tr").each(function () {
        if ($(this).find("input[name='idFicha[]']").val() == idFicha) {
          // Si encuentra una coincidencia de ID, marca fichaYaAgregado como true
          fichaYaAgregado = true;
          // Sale del ciclo
          return false;
        }
      });

      // Si la ficha ya está en la tabla, muestra una alerta usando SweetAlert
      if (fichaYaAgregado) {
        Swal.fire({
          icon: "warning",
          title: "Ficha ya agregada ",
          text: "La Ficha ya está selecionada en la tabla de Fichas."
        });
      } else {
        // traemos los APRENDICES disponibles para asignarlos a la ficha Seleccionada.

        fetch(base_url + "/informes/getInfoAprendicesFicha/" + idFicha)
          .then((res) => res.json())
          .then((data) => {
            data.forEach((data) => {
              let texto = ` <tr id="instru-tr"><td>${data.nombre_completo}</td><td>${data.correo}</td><td style="text-align: center;">${data.accion}</td></tr>  `;
              tablaInfoAprendiz.innerHTML += texto;
            });
          });

        let filaFicha = `
            <tr id="ficha-tr" >
              <td style="font-size: large; text-align: center;">${nombreFicha}</td>
              <td style="font-size: large; text-align: center;">${numeroFicha}</td>
           </tr>
        `;
        $("#tabla-infoFicha tbody").append(filaFicha);
      }
    } else {
      Swal.fire({
        icon: "warning",
        title: "¡ Ya hay una ficha selecionada !",
        text: "Elimina la ficha anterior, para eligir una nueva ficha."
      });
    }
  }
  $(document).on("click", ".eliminar-fila", function () {
    $(this).closest("tr").remove();

    $("#tabla-infoFicha tr").each(function () {
      $("#ficha-tr").remove();
    });

    $("#tabla-aprendices tr").each(function () {
      $("#instru-tr").remove();
    });
  });
});
