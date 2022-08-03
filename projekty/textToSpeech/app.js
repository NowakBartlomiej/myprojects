const userInput = document.getElementById('userInput')
const iconButton = document.querySelector('.icon')
let text = ''

userInput.addEventListener('keyup', () => {
    text = userInput.value
})

iconButton.addEventListener('click', () => {
    let utterance = new SpeechSynthesisUtterance(text)
    utterance.volume = 0.9
    speechSynthesis.speak(utterance)
})


