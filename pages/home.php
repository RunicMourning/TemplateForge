<?php
// pages/home.php

$pageTitle = 'Home';
?>
<h1>Common HTML Elements with Bootstrap 5.3</h1>
 

        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="https://loremflickr.com/320/240?random=1" class="card-img-top" alt="Card Image 1">
                    <div class="card-body">
                        <h5 class="card-title">Card Title 1</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="https://loremflickr.com/320/240?random=2" class="card-img-top" alt="Card Image 2">
                    <div class="card-body">
                        <h5 class="card-title">Card Title 2</h5>
                        <p class="card-text">Another example card with a bit more text to demonstrate card content.</p>
                        <a href="#" class="btn btn-success">Learn more</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="https://loremflickr.com/320/240?random=3" class="card-img-top" alt="Card Image 3">
                    <div class="card-body">
                        <h5 class="card-title">Card Title 3</h5>
                        <p class="card-text">Short card text.</p>
                        <a href="#" class="btn btn-danger">Delete</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="https://loremflickr.com/320/240?random=4" class="card-img-top" alt="Card Image 4">
                    <div class="card-body">
                        <h5 class="card-title">Card Title 4</h5>
                        <p class="card-text">A longer description to show how cards handle varying content lengths.</p>
                        <a href="#" class="btn btn-warning">Edit</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="https://loremflickr.com/320/240?random=5" class="card-img-top" alt="Card Image 5">
                    <div class="card-body">
                        <h5 class="card-title">Card Title 5</h5>
                        <p class="card-text">Another card example.</p>
                        <a href="#" class="btn btn-info">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="https://loremflickr.com/320/240?random=6" class="card-img-top" alt="Card Image 6">
                    <div class="card-body">
                        <h5 class="card-title">Card Title 6</h5>
                        <p class="card-text">Another card example.</p>
                        <a href="#" class="btn btn-info">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="https://loremflickr.com/320/240?random=7" class="card-img-top" alt="Card Image 7">
                    <div class="card-body">
                        <h5 class="card-title">Card Title 7</h5>
                        <p class="card-text">Another card example.</p>
                        <a href="#" class="btn btn-info">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="https://loremflickr.com/320/240?random=8" class="card-img-top" alt="Card Image 8">
                    <div class="card-body">
                        <h5 class="card-title">Card Title 8</h5>
                        <p class="card-text">Another card example.</p>
                        <a href="#" class="btn btn-info">View</a>
                    </div>
                </div>
            </div>
        </div>

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