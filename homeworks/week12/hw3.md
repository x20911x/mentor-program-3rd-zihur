## 請說明 SQL Injection 的攻擊原理以及防範方法
指的是利用 SQL 語法的漏洞在各種能輸入 SQL 語法的地方寫入程式碼，導致資料庫被攻入，一般來說要預防這些攻擊，只要在進行查詢時多加利用模板的方式就能有效避免。
常見寫法：`"password' OR '1'='1"`。

## 請說明 XSS 的攻擊原理以及防範方法
跨站指令碼（Cross-site Scripting） 的攻擊原理是利用網頁輸出時，如 `<`、`>` 等部分字元會被判斷為網頁程式的一部份，進而竄改網頁前端的資料，甚至可直接寫入 JavaScript 語法到網頁中，防範方式是將輸出的資料用跳脫字元處理，php 可用 `htmlspecialchars()` 處理。值得注意的是 JavaScript 在進行 innerText、innerHTML... 等操作時，若沒有處理好跳脫，則仍有可能導致 XSS 攻擊。

## 請說明 CSRF 的攻擊原理以及防範方法
CSRF 是利用當向網站發出請求時，瀏覽器會自動帶入該網站的 cookie 的漏洞進行攻擊，而網站因為通常是依靠 cookie 而進行身分認定，因此攻擊者可以將網址讓其他使用者有意或著是無意間的觸發該網址，進而達成仿造他人身分操作網站。
防範方式有許多，最基本是盡量不使用 get 去取得重要資料，也可以設定 samesite，又或著透過雙重的 cookie 設定，在 post 時額外多 post 一個 csrfToken 在後端進行驗證。