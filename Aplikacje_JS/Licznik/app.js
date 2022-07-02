let count = 0;
const counter = document.getElementById('counter');

function minus() {
    count = count - 1;
    counter.innerHTML = count;
}

function plus() {
    count = count + 1;
    counter.innerHTML = count;
}