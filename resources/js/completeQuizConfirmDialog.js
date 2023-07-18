const confirmDialog = document.querySelector('#confirmDialog');

const confirmYesButton = document.querySelector('#confirmYes');
const confirmNoButton = document.querySelector('#confirmNo');

const completeButton = document.querySelector('#complete');

completeButton.addEventListener('click', () => {
    confirmDialog.classList.remove('hidden');
});

confirmNoButton.addEventListener('click', () => {
    confirmDialog.classList.add('hidden');
});