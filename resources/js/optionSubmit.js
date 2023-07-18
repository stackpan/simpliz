const answerRadios = document.querySelectorAll('input[type="radio"][name="optionId"]');
const submitBtn = document.querySelector('#submitBtn');

answerRadios.forEach(function(radio) {
    radio.addEventListener('click', function() {
        submitBtn.click();
    });
});
