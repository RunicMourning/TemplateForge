<?php
// pages/home.php

// Define the title for this page.
$pageTitle = "Home";
//$headerIncludes[] = "Content";
//$footerIncludes[] = "Content";
?>
<h1>h1. heading</h1>
<h2>h2. heading</h2>
<h3>h3. heading</h3>
<h4>h4. heading</h4>
<h5>h5. heading</h5>
<h6>h6. heading</h6>

<h3>
  Fancy display heading
  <small class="text-muted">With faded secondary text</small>
</h3>

<h1 class="display-1">Display 1</h1>
<h1 class="display-2">Display 2</h1>
<h1 class="display-3">Display 3</h1>
<h1 class="display-4">Display 4</h1>
<h1 class="display-5">Display 5</h1>
<h1 class="display-6">Display 6</h1>

<p class="lead">
  Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est
  non commodo luctus.
</p>

<p>You can use the mark tag to <mark>highlight</mark> text.</p>
<p><del>This line of text is meant to be treated as deleted text.</del></p>
<p>
  <s>This line of text is meant to be treated as no longer accurate.</s>
</p>
<p>
  <ins>This line of text is meant to be treated as an addition to the document.</ins>
</p>
<p><u>This line of text will render as underlined</u></p>
<p>
  <small>This line of text is meant to be treated as fine print.</small>
</p>
<p><strong>This line rendered as bold text.</strong></p>
<p><em>This line rendered as italicized text.</em></p>

<div class="alert alert-primary mb-3" role="alert">
  <strong>Note primary:</strong> Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cum
  doloremque officia laboriosam. Itaque ex obcaecati architecto! Qui necessitatibus delectus
  placeat illo rem id nisi consequatur esse, sint perspiciatis soluta porro?
</div>

<div class="alert alert-secondary mb-3" role="alert">
  <strong>Note secondary:</strong> Lorem, ipsum dolor sit amet consectetur adipisicing elit.
  Cum doloremque officia laboriosam. Itaque ex obcaecati architecto! Qui necessitatibus
  delectus placeat illo rem id nisi consequatur esse, sint perspiciatis soluta porro?
</div>

<div class="alert alert-success mb-3" role="alert">
  <strong>Note success:</strong> Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cum
  doloremque officia laboriosam. Itaque ex obcaecati architecto! Qui necessitatibus delectus
  placeat illo rem id nisi consequatur esse, sint perspiciatis soluta porro?
</div>

<div class="alert alert-danger mb-3" role="alert">
  <strong>Note danger:</strong> Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cum
  doloremque officia laboriosam. Itaque ex obcaecati architecto! Qui necessitatibus delectus
  placeat illo rem id nisi consequatur esse, sint perspiciatis soluta porro?
</div>

<div class="alert alert-warning mb-3" role="alert">
  <strong>Note warning:</strong> Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cum
  doloremque officia laboriosam. Itaque ex obcaecati architecto! Qui necessitatibus delectus
  placeat illo rem id nisi consequatur esse, sint perspiciatis soluta porro?
</div>

<div class="alert alert-info mb-3" role="alert">
  <strong>Note info:</strong> Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cum
  doloremque officia laboriosam. Itaque ex obcaecati architecto! Qui necessitatibus delectus
  placeat illo rem id nisi consequatur esse, sint perspiciatis soluta porro?
</div>

<div class="alert alert-light mb-3" role="alert">
  <strong>Note light:</strong> Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cum
  doloremque officia laboriosam. Itaque ex obcaecati architecto! Qui necessitatibus delectus
  placeat illo rem id nisi consequatur esse, sint perspiciatis soluta porro?
</div>

<div class="alert alert-dark text-secondary mb-0" role="alert">
  <strong>Note light:</strong> Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cum
  doloremque officia laboriosam. Itaque ex obcaecati architecto! Qui necessitatibus delectus
  placeat illo rem id nisi consequatur esse, sint perspiciatis soluta porro?
</div>

<figure>
  <blockquote class="blockquote">
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
  </blockquote>
  <figcaption class="blockquote-footer">
    Someone famous in <cite title="Source Title">Source Title</cite>
  </figcaption>
</figure>

<ul class="list-unstyled">
  <li>Lorem ipsum dolor sit amet</li>
  <li>Consectetur adipiscing elit</li>
  <li>Integer molestie lorem at massa</li>
  <li>Facilisis in pretium nisl aliquet</li>
  <li>
    Nulla volutpat aliquam velit
    <ul>
      <li>Phasellus iaculis neque</li>
      <li>Purus sodales ultricies</li>
      <li>Vestibulum laoreet porttitor sem</li>
      <li>Ac tristique libero volutpat at</li>
    </ul>
  </li>
  <li>Faucibus porta lacus fringilla vel</li>
  <li>Aenean sit amet erat nunc</li>
  <li>Eget porttitor lorem</li>
</ul>

<ul class="list-inline">
  <li class="list-inline-item">Lorem ipsum</li>
  <li class="list-inline-item">Phasellus iaculis</li>
  <li class="list-inline-item">Nulla volutpat</li>
</ul>

<dl class="row">
  <dt class="col-sm-3">Description lists</dt>
  <dd class="col-sm-9">A description list is perfect for defining terms.</dd>
  <dt class="col-sm-3">Euismod</dt>
  <dd class="col-sm-9">
    <p>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</p>
    <p>Donec id elit non mi porta gravida at eget metus.</p>
  </dd>
  <dt class="col-sm-3">Malesuada porta</dt>
  <dd class="col-sm-9">Etiam porta sem malesuada magna mollis euismod.</dd>
  <dt class="col-sm-3 text-truncate">Truncated term is truncated</dt>
  <dd class="col-sm-9">
    Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.
  </dd>
  <dt class="col-sm-3">Nesting</dt>
  <dd class="col-sm-9">
    <dl class="row">
      <dt class="col-sm-4">Nested definition list</dt>
      <dd class="col-sm-8">Aenean posuere, tortor sed cursus feugiat, nunc augue blandit nunc.</dd>
    </dl>
  </dd>
</dl>

