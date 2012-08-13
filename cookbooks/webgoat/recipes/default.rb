package "tomcat6"

script "webgoat_download" do
  interpreter "bash"
  user "root"
  code <<-EOH
  wget http://webgoat.googlecode.com/files/WebGoat-5.4.war -O /tmp/WebGoat.war
  cp /tmp/WebGoat.war /var/lib/tomcat6/webapps/WebGoat.war
  EOH
end

template "/etc/tomcat6/tomcat-users.xml" do
  source "tomcat-users.xml.erb"
  owner "root"
  group "tomcat6"
  mode "0640"
  variables(
    :users => node[:tomcat][:users],
    :roles => node[:tomcat][:roles]
  )
end

service "tomcat6" do
  action :restart
end
