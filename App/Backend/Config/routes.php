<?xml version="1.0" encoding="utf-8" ?>
<routes>
  <route url="/projet_formation/web/admin/" module="News" action="index" />
  <route url="/projet_formation/web/admin/news-insert\.html" module="News" action="insert" />
  <route url="/projet_formation/web/admin/news-update-([0-9]+)\.html" module="News" action="update" vars="id" />
  <route url="/projet_formation/web/admin/news-delete-([0-9]+)\.html" module="News" action="delete" vars="id" />
  <route url="/projet_formation/web/admin/comment-update-([0-9]+)\.html" module="News" action="updateComment" vars="id" />
  <route url="/projet_formation/web/admin/comment-delete-([0-9]+)\.html" module="News" action="deleteComment" vars="id" />
</routes>