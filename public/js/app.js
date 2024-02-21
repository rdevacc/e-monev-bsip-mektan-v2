document.querySelector("#pengaduan_id").addEventListener("change", function(e) {
    const pengaduan_id = document.querySelector("#pengaduan_id");
    const pj_id = document.querySelector("#pj_id");

    pj_id.value = pengaduan_id.value;
  });