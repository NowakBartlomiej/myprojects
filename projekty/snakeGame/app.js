import { update as updateSnake, draw as drawSnake, SNAKE_SPEED, getSnakeHead, snakeIntersection } from './Snake.js'
import { update as updateFood, draw as drawFood }from './Food.js'
import { outsideGrid } from './grid.js'

let lastRenderTime = 0
let gameOver = false
const gameBoard = document.getElementById('game-board')


const main = (currentTime) => {
    if (gameOver) {
        if (confirm('You Lost. Press OK to restart')) {
            window.location = './index.html'
        }
        return
    }
    
    window.requestAnimationFrame(main)
    const secondsSinceLastRender = (currentTime - lastRenderTime) / 1000
    if (secondsSinceLastRender < 1 / SNAKE_SPEED) {
        return
    }
    lastRenderTime = currentTime
    // console.log('render')

    update()
    draw()
}

window.requestAnimationFrame(main)

const update = () => {
    updateSnake()
    updateFood()
    checkDeath()
}

const draw = () => {
    gameBoard.innerHTML = ''
    drawSnake(gameBoard)
    drawFood(gameBoard)
}

const checkDeath = () => {
    gameOver = outsideGrid(getSnakeHead()) || snakeIntersection()
}