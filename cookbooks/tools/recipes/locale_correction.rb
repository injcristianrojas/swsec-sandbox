execute 'locale_correction' do
    command 'locale-gen es_CL.UTF-8 && dpkg-reconfigure locales'
end

