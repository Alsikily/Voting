let addChoose = document.querySelector('.add-choose button');
addChoose.addEventListener('click', () => {
    let chooseValue = document.querySelector('.add-choose > input.choose');
    addInputWithChooseValue(chooseValue.value);
    chooseValue.value = '';
});

function addInputWithChooseValue(value) {
    let chooses = document.querySelector('.vote-chooses');
    let newInput = document.createElement('input');
    newInput.classList.add('form-control');
    newInput.setAttribute('type', 'text');
    newInput.setAttribute('readonly', 'on');
    newInput.setAttribute('name', 'chooses[]');
    newInput.setAttribute('value', value);
    chooses.appendChild(newInput);
}