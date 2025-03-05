<?php
// pages/home.php

$pageTitle = 'Home';
?>
<h1>Common HTML Elements with Bootstrap 5.3</h1>

        <hr> 

        <h2>Images</h2>
        <img src="https://loremflickr.com/320/240" class="img-fluid mb-3" alt="Placeholder Image 320x240">
        <img src="https://loremflickr.com/640/480" class="img-fluid mb-3" alt="Placeholder Image 640x480">
        <img src="https://loremflickr.com/200/150" class="img-fluid mb-3" alt="Placeholder Image 200x150">

        <hr>

        <h2>Paragraphs</h2>
        <p>This is a paragraph of text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        <p>Another paragraph. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>

        <hr>f
        <h2>Headings</h2>
        <h1>Heading 1</h1>
        <h2>Heading 2</h2>
        <h3>Heading 3</h3>
        <h4>Heading 4</h4>
        <h5>Heading 5</h5>
        <h6>Heading 6</h6>

        <hr>

        <h2>Lists</h2>
        <ul> 
            <li>Item 1</li>
            <li>Item 2</li>
            <li>Item 3</li>
        </ul>

        <ol> 
            <li>First item</li>
            <li>Second item</li>
            <li>Third item</li>
        </ol>

        <hr>

        <h2>Forms</h2>
        <form>
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Your Name">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Your Email">
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message:</label>
                <textarea id="message" name="message" rows="4" class="form-control" placeholder="Your Message"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <hr>

        <h2>Tables</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Header 1</th>
                    <th>Header 2</th>
                    <th>Header 3</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Data 1</td>
                    <td>Data 2</td>
                    <td>Data 3</td>
                </tr>
                <tr>
                    <td>Data 4</td>
                    <td>Data 5</td>
                    <td>Data 6</td>
                </tr>
            </tbody>
        </table>

        <hr>

        <h2>Divs and Spans</h2>
        <div>This is a <span>span</span> inside a div.</div>
        <div>Another div.</div>

        <hr>

        <h2>Buttons</h2>
        <button class="btn btn-primary">Click Me</button>
        <input type="button" value="Input Button" class="btn btn-secondary">

        <hr>

        <h2>Links</h2>
        <a href="https://www.example.com">Example Link</a>