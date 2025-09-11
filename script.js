// Plant Disease Detection
function uploadImage() {
  var form = document.getElementById("diseaseForm");
  var formData = new FormData(form);
  document.getElementById("loading").style.display = "block";
  fetch("disease_detect.php", {
    method: "POST",
    body: formData,
  })
    .then((resp) => resp.json())
    .then((data) => {
      document.getElementById("loading").style.display = "none";
      if (data.success) {
        document.getElementById("disease-result").innerHTML = `
      
        <h3>Disease Detection Result</h3>
        <p><b>Disease:</b> ${data.disease}</p>
        <p><b>Confidence:</b> ${data.confidence}</p>
        <img src="${data.image_path}" width="200" />
        <h4>Symptoms:</h4>
        <p>${data.symptoms}</p>
        <h4>Treatment:</h4>
        <p>${data.treatment}</p>
        <h4>Prevention:</h4>
        <p>${data.prevention}</p>
        
      `;
      } else {
        document.getElementById("disease-result").innerHTML =
          '<span style="color:red;">Detection Failed.</span>';
      }
    });
}

// Soil → Crop Recommendation
function recommendCrops() {
  var form = document.getElementById("soilForm");
  var formData = new FormData(form);
  document.getElementById("crop-result").innerHTML = "Loading...";
  fetch("recommendations.php", {
    method: "POST",
    body: formData,
  })
    .then((resp) => resp.json())
    .then((data) => {
      if (data.success) {
        let html = `
        <h3>Crop Recommendations</h3>
        <table border="1" cellpadding="8" cellspacing="0">
          <tr>
            <th>Crop</th><th>Season</th><th>Duration</th><th>Fertilizer</th><th>Pesticide</th><th>Confidence</th>
          </tr>
      `;
        data.data.forEach((row) => {
          html += `<tr>
          <td>${row.crop}</td>
          <td>${row.season}</td>
          <td>${row.duration}</td>
          <td>${row.fertilizer}</td>
          <td>${row.pesticide}</td>
          <td>${row.confidence}</td>
        </tr>`;
        });
        html += "</table>";
        document.getElementById("crop-result").innerHTML = html;
      } else {
        document.getElementById("crop-result").innerHTML =
          '<span style="color:red;">Recommendation Failed.</span>';
      }
    });
}
