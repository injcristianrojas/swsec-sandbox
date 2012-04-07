# Gems Ruby
=begin
gems = %w{rails brakeman}

gems.each do |gem|
  gem_package(gem) do
    action :install
  end
end
=end

execute 'rails install' do
  command 'gem install rails -v 1.2.6'
end

execute 'brakeman install' do
  command 'gem install brakeman'
end
