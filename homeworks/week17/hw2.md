請說明以下程式碼會輸出什麼，以及盡可能詳細地解釋原因。

``` js
for(var i=0; i<5; i++) {
  console.log('i: ' + i)
  setTimeout(() => {
    console.log(i)
  }, i * 1000)
}
```
最終結果應該是...
```js
i: 0
i: 1
i: 2
i: 3
i: 4
5       // 0 秒鐘後印出
5       // 1 秒鐘後印出
5       // 2 秒鐘後印出
5       // 3 秒鐘後印出
5       // 4 秒鐘後印出
```

來試著解釋原因...
先我們簡化這段程式碼看看：
```js
for (var i = 0; i < 5; i++) {
  console.log('i: ' + i)
}
```
這是一段很基本的迴圈程式碼，這邊很明顯可以看到 i 的確是從 0 開始遞增上去到 4，最後結束了迴圈。
但是跑完迴圈之後不知道你有沒有想過此時的 i 是什麼呢？
沒錯，答案是 5，原因在跑 for 迴圈的時候，過程其實是像這樣的，但我拿掉了判斷式。
```js
// 第一圈
var i = 0
console.log('i: ' + i); // 會先判斷 i 是否小於 5

// 第二圈
i += 1                  // 此時的 i 變成 1
console.log('i: ' + i); // 同樣先判斷 i 是否小於 5
//...以下快轉

// 第六圈
i += 1                  // 此時的 i 變成 5
console.log('i: ' + i); // 因為不符合 i < 5 的條件，跳出迴圈，不會 log，且此時 i = 5;
```
好，上面的說明大概解釋了為什麼會有 5 的原因，但似乎不能充分解釋為什麼下面這段程式碼放在 for 迴圈會連續出現 5 個 5，而且明明有照著所期望的秒數的印出，代表 i * 1000 的 i 在這裡的確是 0 ~ 4 阿？
```js
for (var i = 0; i < 5; i++) {
  setTimeout(() => {
    console.log(i)
  }, i * 1000)
}
```
嘿！還記得 Event Loop 機制嗎？
這邊的 setTimeout() 可是一個延時設計的 function 呢，所以實際上每跑了一次的迴圈，就產生了一次延時 function 並且先被放到了 task queue 裡面，這時候 function 裡面的 `console.log(i)` 也因為還沒有觸發到執行階段，想當然此時裡面的 i 也還沒有被賦值囉，必須要等到 for 迴圈跑完了，才會去執行 task queue 的 function，也因為 for 迴圈已經跑完了，所以這時候的 i 實際上已經變成了 5，此時裡面的 i 才被正式賦值為 5 這麼一來就能解釋明白了。

那麼，我們要怎麼樣才能做到依照秒數印出相對應的數字呢？
有個方法就是利用 function。
這邊要簡單補充說明一個機制，就是所謂的 scope，中文稱為作用域，這翻譯我覺得蠻直覺的，說明白了就是一個**變數**可以發生**作用**的**區域**，而在 ES6 之後 JavaScript 的世界裡面對於可以簡單看成是以 `{ }` 作為變數生存的範圍，除此之外，JS 的作用域有個特性，那就是當內層找不到變數時，會自動向上一層尋找到外層的變數，但外層卻不能存取到內層的變數。請注意對於 `var` 所宣告的變數，只有 function 能夠產生作用域

回過頭來說，利用 function 的獨立作用域，我們可以在 function 內獨立賦予 i 值，而不要被賦值到全域的變數就可以啦，還記得我們在跑迴圈時實際上產生了 5 個 setTimeout() 嗎？因此我們要做的就是利用參數傳遞的方式把正確的 i 值傳進 function 內，如果不是很能明白，直接看下面這段程式碼。
```js
function printTime(num) {
  setTimeout(() => {
    console.log(num)
  }, num * 1000)
  return
}

for (var i = 0; i < 5; i++) {
  printTime(i)
}
```

但如果你對 `setTimeout()` 足夠熟悉，其實可以直接傳進參數，只要這樣寫就好
```js
for (var i = 0; i < 5; i++) {
  setTimeout( i => {
    console.log(i)
  }, i * 1000, i)
}
```

如果你覺得連上述第二種方法都太難理解了，在 ES6 之後有個更方便的作法，只要將 var 改成 let 就好了，因為 let 在迴圈內會自行產生一個 { } 變成塊級作用域，這麼一來，就會有各自獨立的作用域了。
```js
for (let i = 0; i < 5; i++) {
  setTimeout(() => {
    console.log(i)
  }, i * 1000)
}
```
用 let 跑迴圈，運作上可以簡單看成是下面這樣
```js
{ // 塊級作用域
  let i = 0
  setTimeout(() => {
    console.log(i)
  }, i * 1000)
}

{ // 塊級作用域
  let i = 1
  setTimeout(() => {
    console.log(i)
  }, i * 1000)
}
```