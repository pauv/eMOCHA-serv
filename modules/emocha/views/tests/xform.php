<html
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xf="http://www.w3.org/2002/xforms"
>
  <head>
    <title>XForms input and output controls</title>
    <script src="/js/backplane/backplane.js" type="text/javascript">/**/</script>
	<link rel="stylesheet" href="/js/backplane/assets/backplane.css"></link>
  </head>
  <body>
    <xf:model>
      <xf:instance>
        <data xmlns="">
          <firstname>John</firstname>
          <surname>Doe</surname>
        </data>
      </xf:instance>
    </xf:model>

    <xf:input ref="firstname">
      <xf:label>First Name:</xf:label>
    </xf:input>
    
    <xf:input ref="surname">
      <xf:label>Surname:</xf:label>
    </xf:input>

    <xf:output ref="firstname"><xf:label>First Name:</xf:label></xf:output>
    <xf:output ref="surname"><xf:label>Surname:</xf:label></xf:output>
  </body>
</html>