<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"
        />
        <title>Snake Game</title>
        <style>
            #gameCanvas {
                background-color: #000;
                display: block;
                margin: 0 auto;
                width: 70%;
            }

            .game-over {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: rgba(0, 0, 0, 0.8);
                color: #fff;
                padding: 20px;
                border-radius: 10px;
                text-align: center;
                font-size: 24px;
                display: none;
            }

            .game-over button {
                background-color: #f00;
                color: #fff;
                border: none;
                padding: 10px 20px;
                margin-top: 10px;
                cursor: pointer;
            }

            .game-over button:hover {
                background-color: #d00;
            }

            body {
                background-image: url("https://bbsrwlwjaotuuufnxowi.supabase.co/storage/v1/object/public/sliki/wallpaper_for_a_retro_snake_game_with_a_lot_of_.jpg");
                background-size: repeat;
            }

            h1 {
                text-align: center;
                font-size: 36px;
                color: red;
                margin-top: 20px;
                font-weight: bold;
                background-color: white;
            }

            #footer {
                text-align: center;
                font-size: 25px;
                color: red;
                margin-top: 20px;
                font-weight: bold;
                background-color: white;
            }

            #creatorName {
                font-weight: bold;
            }

            #startButton {
                display: block;
                margin: 20px auto;
                background-color: red;
                color: #fff;
                border: none;
                padding: 10px 20px;
                cursor: pointer;
                font-size: 18px;
                border-radius: 5px;
            }

            #instructionScreen {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                background-color: rgba(0, 0, 0, 0.8);
                color: #fff;
                padding: 20px;
                border-radius: 10px;
                text-align: center;
                font-size: 18px;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

            @media only screen and (max-width: 600px) {
                #gameCanvas {
                    width: 100%;
                }

                #instructionScreen {
                    font-size: 16px;
                    position: relative;
                    top: auto;
                    left: auto;
                    transform: none;
                    margin-top: 20px;
                }

                h1 {
                    font-size: 24px;
                }

                #footer {
                    font-size: 20px;
                }

                #startButton {
                    font-size: 14px;
                }
            }

            #gameOverScreen {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: rgba(0, 0, 0, 0.8);
    color: #fff;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    font-size: 24px;
    display: none;
}

@media only screen and (max-width: 600px) {
    #gameOverScreen {
        position: static;
        transform: none;
        margin-top: 20px;
    }
}
#inputName {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    font-size: 18px;
    border: 2px solid red;
    border-radius: 5px;
    color: red;
    background-color: transparent;
    outline: none;
}

#saveScoreButton {
    background-color: red;
    color: #fff;
    border: none;
    padding: 10px 20px;
    margin-top: 10px;
    cursor: pointer;
    font-size: 18px;
    border-radius: 5px;
}

#saveScoreButton:hover {
    background-color: darkred;
}

#playAgainButton {
    background-color: red;
    color: #fff;
    border: none;
    padding: 10px 20px;
    margin-top: 10px;
    cursor: pointer;
    font-size: 18px;
    border-radius: 5px;
}

#playAgainButton:hover {
    background-color: darkred;
}

        </style>
    </head>

    <body>
        <h1>Snake Game</h1>
        <canvas id="gameCanvas"></canvas>
        <div id="instructionScreen">
            <h2>Instructions</h2>
            <p>Use the arrow keys or swipe to control the snake:</p>
            <ul>
                <li>Arrow Up / Swipe Up: Move Up</li>
                <li>Arrow Down / Swipe Down: Move Down</li>
                <li>Arrow Left / Swipe Left: Move Left</li>
                <li>Arrow Right / Swipe Right: Move Right</li>
            </ul>
            <button id="startButton" onclick="startGame()">Start Game</button>
            <button onclick="window.location.href='/leaderboard'" style="display: block; margin: 20px auto; background-color: green; color: #fff; border: none; padding: 10px 20px; cursor: pointer; font-size: 18px; border-radius: 5px;">
                View Leaderboard
            </button>
        </div>
        <p id="footer">
            Created by <span id="creatorName">Jovan Ilovski</span> -
            <span id="currentYear"></span>
        </p>
        <div id="gameOverScreen" class="game-over">
            <p>Game Over</p>
            <p id="scoreParagraph"></p>
            <input id="inputName" type="text" placeholder="Enter your name" />
            <button id="saveScoreButton" onclick="saveScore()">
                Save Score
            </button>
            <button id="playAgainButton" onclick="startGame()">
                Play Again
            </button>
        </div>
        <script>
            const canvas = document.getElementById("gameCanvas");
            const ctx = canvas.getContext("2d");
            const scale = 10;
            const rows = canvas.height / scale;
            const columns = canvas.width / scale;
            let snake,
                fruit,
                gameInterval,
                gameOverScreen,
                countdownInterval,
                score = 0;

            let keyState = {};
            let touchStartX = 0;
            let touchStartY = 0;

            document.addEventListener("keydown", (event) => {
                keyState[event.key] = true;
            });

            document.addEventListener("keyup", (event) => {
                keyState[event.key] = false;
            });

            document.addEventListener("touchstart", handleTouchStart);
            document.addEventListener("touchend", handleTouchEnd);

            function handleTouchStart(event) {
                touchStartX = event.touches[0].clientX;
                touchStartY = event.touches[0].clientY;
            }

            function handleTouchEnd(event) {
                const touchEndX = event.changedTouches[0].clientX;
                const touchEndY = event.changedTouches[0].clientY;

                const deltaX = touchEndX - touchStartX;
                const deltaY = touchEndY - touchStartY;

                if (Math.abs(deltaX) > Math.abs(deltaY)) {
                    if (deltaX > 0 && snake.direction !== "left") {
                        snake.changeDirection("right");
                    } else if (deltaX < 0 && snake.direction !== "right") {
                        snake.changeDirection("left");
                    }
                } else {
                    if (deltaY > 0 && snake.direction !== "up") {
                        snake.changeDirection("down");
                    } else if (deltaY < 0 && snake.direction !== "down") {
                        snake.changeDirection("up");
                    }
                }
            }

            document
                .getElementById("startButton")
                .addEventListener("click", startGame);

            function startGame() {
                if (window.innerWidth < window.innerHeight) {
                    alert(
                        "Please flip your device horizontally for a better gaming experience."
                    );
                    return;
                }

                const canvas = document.getElementById("gameCanvas");
                if (!document.fullscreenElement) {
                    canvas.requestFullscreen().catch((err) => {
                        console.error(
                            `Error attempting to enable full-screen mode: ${err.message}`
                        );
                    });
                }

                clearInterval(gameInterval);
                clearInterval(countdownInterval);

                if (gameOverScreen) {
                    gameOverScreen.style.display = "none";
                }

                const instructionScreen =
                    document.getElementById("instructionScreen");
                instructionScreen.style.display = "none";

                ctx.clearRect(0, 0, canvas.width, canvas.height);
                snake = new Snake();
                fruit = new Fruit();
                fruit.pickLocation();

                let countdown = 3;

                countdownInterval = setInterval(() => {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.fillStyle = "#fff";
                    ctx.font = "36px Arial";
                    ctx.textAlign = "center";
                    ctx.fillText(
                        countdown,
                        canvas.width / 2,
                        canvas.height / 2
                    );

                    countdown--;

                    if (countdown < 0) {
                        clearInterval(countdownInterval);

                        gameInterval = setInterval(() => {
                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                            fruit.draw();
                            snake.move();
                            snake.draw();

                            if (snake.eat(fruit)) {
                                fruit.pickLocation();
                                score += getNextFruitPoints();
                            }

                            if (snake.checkCollision()) {
                                showGameOver();
                            }
                        }, 100);
                    }
                }, 1000);
            }

            function showGameOver() {
                clearInterval(gameInterval);
                clearInterval(countdownInterval);

                gameOverScreen = document.getElementById("gameOverScreen");
                const scoreParagraph =
                    document.getElementById("scoreParagraph");
                scoreParagraph.textContent = `Your Score: ${score}`;

                gameOverScreen.style.display = "block";

                if (document.fullscreenElement) {
                    document.exitFullscreen();
                }
            }

            function getNextFruitPoints() {
                return score === 0 ? 1 : 2 * Math.floor(Math.sqrt(score + 1));
            }

            class Snake {
                constructor() {
                    this.body = [new Cell(5, 5)];
                    this.direction = "right";
                }

                draw() {
                    for (let i = 0; i < this.body.length; i++) {
                        this.body[i].draw();
                    }
                }

                move() {
                    const head = this.body[this.body.length - 1];
                    const newHead = new Cell(head.x, head.y);

                    if (this.direction === "right") {
                        newHead.x += 1;
                    } else if (this.direction === "left") {
                        newHead.x -= 1;
                    } else if (this.direction === "up") {
                        newHead.y -= 1;
                    } else if (this.direction === "down") {
                        newHead.y += 1;
                    }

                    this.body.push(newHead);

                    if (!this.eat(fruit)) {
                        this.body.shift();
                    }
                }

                changeDirection(newDirection) {
                    this.direction = newDirection;
                }

                eat(fruit) {
                    const head = this.body[this.body.length - 1];
                    if (head.x === fruit.x && head.y === fruit.y) {
                        return true;
                    }
                    return false;
                }

                checkCollision() {
                    const head = this.body[this.body.length - 1];

                    if (
                        head.x < 0 ||
                        head.x >= columns ||
                        head.y < 0 ||
                        head.y >= rows ||
                        this.checkSelfCollision()
                    ) {
                        return true;
                    }

                    return false;
                }

                checkSelfCollision() {
                    const head = this.body[this.body.length - 1];

                    for (let i = 0; i < this.body.length - 1; i++) {
                        if (
                            head.x === this.body[i].x &&
                            head.y === this.body[i].y
                        ) {
                            return true;
                        }
                    }

                    return false;
                }

                getScore() {
                    return score;
                }
            }

            class Cell {
                constructor(x, y) {
                    this.x = x;
                    this.y = y;
                }

                draw() {
                    ctx.fillStyle = "#3622ba";
                    ctx.fillRect(this.x * scale, this.y * scale, scale, scale);
                }
            }

            class Fruit {
                constructor() {
                    this.x;
                    this.y;
                }

                pickLocation() {
                    this.x = Math.floor(Math.random() * columns);
                    this.y = Math.floor(Math.random() * rows);
                }

                draw() {
                    ctx.fillStyle = "#3ec94a";
                    ctx.fillRect(this.x * scale, this.y * scale, scale, scale);
                }
            }

            function handleArrowKeys() {
                if (keyState["ArrowRight"] && snake.direction !== "left") {
                    snake.changeDirection("right");
                } else if (
                    keyState["ArrowLeft"] &&
                    snake.direction !== "right"
                ) {
                    snake.changeDirection("left");
                } else if (keyState["ArrowUp"] && snake.direction !== "down") {
                    snake.changeDirection("up");
                } else if (keyState["ArrowDown"] && snake.direction !== "up") {
                    snake.changeDirection("down");
                }
            }

            setInterval(handleArrowKeys, 100);

            function updateFooter() {
                const currentYear = new Date().getFullYear();
                const creatorName = "Jovan Ilovski";
                document.getElementById("currentYear").textContent =
                    currentYear;
                document.getElementById("creatorName").textContent =
                    creatorName;
            }

            function saveScore() {
    
    document.getElementById("inputName").style.display = "none";
    document.getElementById("saveScoreButton").style.display = "none";

    const playerName = document.getElementById("inputName").value.trim();
    if (playerName !== "") {
        axios
            .post("/store-score", { name: playerName, score })
            .then((response) => {
                console.log(response.data);
            })
            .catch((error) => {
                console.error(error);
            });
    }
}
            window.addEventListener("load", updateFooter);
        </script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    </body>
</html>
