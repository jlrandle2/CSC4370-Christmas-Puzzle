let size = 4; 
let tiles = []; 
let moves = 0;
let timerInterval = null;
let seconds = 0;


document.addEventListener('DOMContentLoaded', () => {
    startNewGame();
    
    
});


function startNewGame() {
    moves = 0;
    seconds = 0;
    updateStats();
    clearInterval(timerInterval);
    startTimer();
    
    
    let solvable = false;
    while (!solvable) {
       
        tiles = [];
        for (let i = 1; i < size * size; i++) {
            tiles.push(i);
        }
        tiles.push(0);

        
        tiles.sort(() => Math.random() - 0.5);

        
        if (isSolvable(tiles)) {
            solvable = true;
        }
    }

    renderBoard();
}

function renderBoard() {
    const board = document.getElementById('puzzle-board');
    board.innerHTML = ''; 
    
    
    board.style.gridTemplateColumns = `repeat(${size}, 100px)`;

    tiles.forEach((tileNumber, index) => {
        const tileDiv = document.createElement('div');
        tileDiv.classList.add('tile');
        
        if (tileNumber === 0) {
            tileDiv.classList.add('empty-tile'); 
        } else {
            tileDiv.textContent = tileNumber;
           
            tileDiv.onclick = () => handleTileClick(index);
        }
        board.appendChild(tileDiv);
    });
}


function handleTileClick(index) {
    const emptyIndex = tiles.indexOf(0);
    
    
    const isAdjacent = 
        Math.abs(index - emptyIndex) === 1 || 
        Math.abs(index - emptyIndex) === size;

    if (isAdjacent) {
        
        [tiles[index], tiles[emptyIndex]] = [tiles[emptyIndex], tiles[index]];
        
        moves++;
        updateStats();
        renderBoard();
        checkWin();
    }
}


function checkWin() {

    const isSolved = tiles.every((val, i) => {
        
        if (i === tiles.length - 1) return val === 0;
        return val === i + 1;
    });

    if (isSolved) {
        clearInterval(timerInterval);
        alert(`You Won! Moves: ${moves} Time: ${seconds}s`);
        
    }
}


function startTimer() {
    timerInterval = setInterval(() => {
        seconds++;
        updateStats();
    }, 1000);
}

function updateStats() {
    document.getElementById('move-count').innerText = moves;
    
    
    const mins = Math.floor(seconds / 60).toString().padStart(2, '0');
    const secs = (seconds % 60).toString().padStart(2, '0');
    document.getElementById('timer').innerText = `${mins}:${secs}`;

    function getInversionCount(arr) {
        let inv_count = 0;
        for (let i = 0; i < arr.length - 1; i++) {
            for (let j = i + 1; j < arr.length; j++) {
               
                if (arr[i] > 0 && arr[j] > 0 && arr[i] > arr[j]) {
                    inv_count++;
                }
            }
        }
        return inv_count;
    }
   
    function isSolvable(tileArray) {
        const inversionCount = getInversionCount(tileArray);
        const blankIndex = tileArray.indexOf(0);
        const blankRowFromBottom = 4 - Math.floor(blankIndex / 4);
    
        if (blankRowFromBottom % 2 === 0) {
           
            return (inversionCount % 2 !== 0);
        } else {
            
            return (inversionCount % 2 === 0);
        }
    }
}