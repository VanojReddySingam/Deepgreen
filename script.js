function uploadImage() {
  var form = document.getElementById('diseaseForm');
  var formData = new FormData(form);
  document.getElementById('loading').style.display = 'block';
  fetch('disease_detect.php', {
    method: 'POST',
    body: formData
  })
  .then(resp => resp.json())
  .then(data => {
    document.getElementById('loading').style.display = 'none';
    if (data.success) {
      document.getElementById('disease-result').innerHTML = `
        <h3>Disease Detection Result</h3>
        <p><b>Disease:</b> ${data.disease}</p>
        <p><b>Confidence:</b> ${data.confidence}</p>
        <img src="${data.image_path}" width="200" />
        <h4>Symptoms:</h4>
        <p>${data.symptoms}</p>
        <h4>Treatment:</h4>
        <p>${data.treatment}</p>
      `;
    } else {
      document.getElementById('disease-result').innerHTML = '<span style="color:red;">Detection Failed.</span>';
    }
  });
}
