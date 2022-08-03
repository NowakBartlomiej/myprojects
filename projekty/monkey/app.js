const closedFace = document.querySelector('.closed')
const openedFace = document.querySelector('.open')

closedFace.addEventListener('click' , () => {
    if (openedFace.classList.contains('open')) {
        openedFace.classList.toggle('active')
        closedFace.classList.toggle('active')
    }
})

openedFace.addEventListener('click', () => {
    if (closedFace.classList.contains('closed')) {
        closedFace.classList.toggle('active')
        openedFace.classList.toggle('active')
    }
})