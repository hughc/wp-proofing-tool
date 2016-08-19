## Proofing Tool
A simple Wordpress plugin based on the Google Feedback clone created by [ivoviz](https://github.com/ivoviz/feedback).

Once the plugin is enabled, a "Proofing Tool' popup can be triggered from the bottom right of the front-end.

It uses [html2canvas](http://html2canvas.hertzen.com) to capture the screen, along with highlighted regions. Users can then provide a title and descriptions to accompany the capture. On save, a custom Proofing Note post is created, into which each submitted note and image is saved.

Notes have a custom view interface, allowing the capture to be viewed in a distraction free context.

### potential improvements
 - allow commenting on notes
 - field to indicate issue status (new / pending / wontfix)
 - allow notes to be 'assigned' to other users, triggering email alerts
 - integration with 3rd party systems (wunderlist, jira?)
 - improve note viewing

### known issues
 - does not, and will likely never capture iframe content, so 3rd-party video in particular will not be seen
