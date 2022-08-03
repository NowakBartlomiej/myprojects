const resultDiv = document.getElementById('result')
const computerScoreSpan = document.getElementById('computerScore')
const userScoreSpan = document.getElementById('userScore')

const computerButtons = document.querySelectorAll('.computerBtn')
const buttons = document.querySelectorAll('.userBtn');
const resetButton = document.getElementById('resetGame');

const totalScore = {
    userScore: 0,
    computerScore: 0
}

const getComputerChoice = () => {
    // const computerButtons = document.querySelectorAll('.computerBtn')
    
    const rps = ['rock', 'paper', 'scissors'];
    const randomNuber  = Math.floor(Math.random() * rps.length);
    
    computerButtons.forEach(btn => {
        for (b of computerButtons) {
            b.classList.remove('btnActive')
        }
        
        computerButtons[randomNuber].classList.add('btnActive')
    })

    return rps[randomNuber];
}


const getResult = (playerChoice, computerChoice) => {
    let score = 0;

    if (playerChoice == computerChoice) {
        score = 0;
    }

    else if (playerChoice == 'rock' && computerChoice == 'scissors') {
        score = 1;
    } else if (playerChoice == 'paper' && computerChoice == 'rock') {
        score = 1;
    } else if (playerChoice == 'scissors' && computerChoice == 'paper') {
        score = 1;
    }

    else {
        score = -1;
    }

    return score;
}

const showResult = (score, playerChoice, computerChoice) => {
    // const resultDiv = document.getElementById('result')
    // const computerScoreSpan = document.getElementById('computerScore')
    // const userScoreSpan = document.getElementById('userScore')
    
    if (score == -1) {
        resultDiv.innerHTML = '<h1>You lose!</h1>'
        resultDiv.setAttribute('class', '');
        resultDiv.classList.add('lose');

    } else if (score == 0) {
        resultDiv.innerHTML = '<h1>Its a draw!</h1>'
        resultDiv.setAttribute('class', '');
        resultDiv.classList.add('draw');

    } else {
        resultDiv.innerHTML = '<h1>You win!</h1>'
        resultDiv.setAttribute('class', '')
        resultDiv.classList.add('win')
    }

    computerScoreSpan.innerText = `${totalScore.computerScore}`
    userScoreSpan.innerText = `${totalScore.userScore}`
}

const calculateResult = (playerChoice) => {
    const computerChoice = getComputerChoice();
    const score = getResult(playerChoice, computerChoice);
    totalScore.userScore += score;
    totalScore.computerScore += -score;
    showResult(score, playerChoice, computerChoice);
}

const playGame = () => {
    // const buttons = document.querySelectorAll('.userBtn');
    // const resetButton = document.getElementById('resetGame');

    buttons.forEach(button => {
        button.onclick = () => {
            calculateResult(button.value)
            for (b of buttons) {
                b.classList.remove('btnActive');
            }
            button.classList.add('btnActive')
        };
    })

    resetButton.onclick = () => {
        resetGame(totalScore);
    }

}

const resetGame = (totalScore) => {
    totalScore['userScore'] = 0
    totalScore['computerScore'] = 0

    resultDiv.innerHTML = '';
    computerScoreSpan.innerText = 0;
    userScoreSpan.innerText = 0;

    for (b of computerButtons) {
        b.classList.remove('btnActive')
    }

    for(b of buttons) {
        b.classList.remove('btnActive')
    }
}


playGame()