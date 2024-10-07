Here’s a more in-depth write-up for your TryHackMe room, explaining the vulnerabilities in detail, the exploitation process, and ways to mitigate each issue:

---

### Write-Up: Vulnerability Triad – SQLi, Reflected XSS, and CSRF

In this TryHackMe room, you worked through three of the most common vulnerabilities found in web applications: **SQL Injection (SQLi)**, **Reflected Cross-Site Scripting (XSS)**, and **Cross-Site Request Forgery (CSRF)**. Let’s dive deeper into each of these, how they work, how you exploited them, and how they can be prevented.

---

### 1. SQL Injection (SQLi)

#### Overview:
SQL Injection is a serious vulnerability that allows an attacker to interfere with the queries that an application makes to its database. By injecting malicious SQL statements into an input field, attackers can:
- Bypass authentication.
- Retrieve sensitive information from the database.
- Alter or delete data.
  
In this room, you exploited a vulnerable login form where user inputs were being directly inserted into a SQL query without proper sanitization.

#### Exploitation:
You tested the login form by inputting special characters to see if the application was vulnerable to SQL injection. The query used to check the login credentials likely looked something like this:
```sql
SELECT * FROM users WHERE username = '$username' AND password = '$password';
```
By injecting a SQL payload like:
```sql
' OR 1=1 --
```
you effectively modified the query to:
```sql
SELECT * FROM users WHERE username = '' OR 1=1 --' AND password = '';
```
The `OR 1=1` part always evaluates to true, which makes the query return a valid result, bypassing the need for a correct username and password.

After successfully logging in, you were able to access restricted pages and capture the flag.

#### Mitigation:
SQL Injection is preventable by:
1. **Using Prepared Statements** (also known as parameterized queries): Prepared statements ensure that user inputs are treated as data, not as part of the SQL query.
   Example:
   ```php
   $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
   $stmt->execute([$username, $password]);
   ```
2. **Input Validation**: Ensure that inputs are sanitized and validated before processing them.
3. **Use ORM frameworks**: Many modern frameworks automatically mitigate SQLi by abstracting direct SQL queries through Object-Relational Mapping (ORM) tools.

---

### 2. Reflected Cross-Site Scripting (XSS)

#### Overview:
Reflected XSS occurs when an application takes user-supplied data and includes it in the response without proper validation or encoding. This allows attackers to inject malicious scripts into a website, which can then execute in the victim’s browser when they interact with the vulnerable page. 

With XSS, an attacker can:
- Steal session cookies.
- Redirect users to malicious sites.
- Deface a web page.
  
In this challenge, the vulnerability was in the search functionality, where the search term was reflected on the page without being sanitized.

#### Exploitation:
You entered a script into the search box:
```html
<script>alert('XSS');</script>
```
Since the search query was directly reflected in the page without any filtering or escaping, your JavaScript was executed when the page loaded, resulting in a pop-up alert with the message "XSS." This confirmed the presence of a reflected XSS vulnerability.

Once the script executed, the flag was revealed on the page.

#### Mitigation:
Preventing XSS involves several strategies:
1. **Input Sanitization**: Always sanitize user inputs by removing or escaping potentially dangerous characters, such as `<`, `>`, and `&`.
2. **Output Encoding**: Ensure that any data inserted into the HTML page is properly encoded. For example, encoding the `<script>` tag as `&lt;script&gt;` prevents it from being interpreted as actual JavaScript.
3. **Content Security Policy (CSP)**: Implement a CSP to restrict which scripts can run on the site. A strong CSP can prevent many XSS attacks by only allowing scripts from trusted sources.
4. **Use Libraries**: Use libraries like DOMPurify to sanitize HTML inputs and prevent dangerous code from executing.

---

### 3. Cross-Site Request Forgery (CSRF)

#### Overview:
CSRF is a type of attack where an attacker tricks a victim into submitting a request on behalf of the authenticated user without their consent. This can lead to actions such as changing a password, transferring funds, or other malicious actions, as long as the user is logged into the vulnerable site.

In this room, you found a form submission feature that didn’t verify the origin of the request, making it vulnerable to CSRF.

#### Exploitation:
You crafted a malicious HTML form that replicated a legitimate form on the vulnerable site:
```html
<form action="http://vulnerable-site.com/submit" method="POST">
   <input type="hidden" name="data" value="malicious_data">
   <input type="submit" value="Submit">
</form>
```
You then hosted this form on another website. When a logged-in user visited the malicious site, the form automatically submitted a request to the vulnerable site without the user’s knowledge. This exploited the CSRF vulnerability, and upon successful submission, you received the flag.

#### Mitigation:
To prevent CSRF attacks, web applications should:
1. **Use Anti-CSRF Tokens**: Generate a unique token for each form submission, and verify that the token is valid before processing the request. The token ensures that the request originated from the legitimate user’s session.
   Example:
   ```php
   <input type="hidden" name="csrf_token" value="GENERATED_TOKEN">
   ```
2. **SameSite Cookies**: Implement the `SameSite` attribute in cookies to restrict cross-site requests.
   Example:
   ```http
   Set-Cookie: sessionid=abc123; SameSite=Strict
   ```
   This prevents cookies from being sent with requests from external sites.
3. **Validate the HTTP Referer Header**: Ensure that the request’s `Referer` header matches the site’s domain, which indicates the request originated from your site.

---

### Final Thoughts:
In this TryHackMe room, you successfully exploited SQL Injection, Reflected XSS, and CSRF vulnerabilities. Each of these bugs presents serious security risks, but they can be mitigated through proper input validation, encoding, and the use of security tokens.

- **SQL Injection**: Prevented with prepared statements and input validation.
- **Reflected XSS**: Mitigated by sanitizing and encoding user inputs, along with enforcing a Content Security Policy.
- **CSRF**: Defended with anti-CSRF tokens, SameSite cookies, and origin checks.

By learning how to exploit these vulnerabilities, you’ve gained a deeper understanding of how attackers think and how to defend against such threats. Applying these lessons in real-world applications will greatly improve their security posture.

Good luck with your future hacking adventures!

---

This expanded write-up includes deeper explanations of the vulnerabilities and their prevention, along with a clear overview of how you solved each task.
