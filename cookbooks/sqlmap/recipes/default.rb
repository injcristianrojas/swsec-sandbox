include_recipe "git"

git "/opt/sqlmap/" do
  repository "git://github.com/sqlmapproject/sqlmap.git"
  reference "master"
  action :sync
  user "root"
  group "root"
end