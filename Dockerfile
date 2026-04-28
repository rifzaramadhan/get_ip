FROM php:8.3-apache

# Copy source code to Apache's document root
COPY get_ip.php /var/www/html/index.php

# Enable mod_remoteip so Apache trusts forwarded IP headers
RUN a2enmod remoteip

# Configure Apache to use X-Forwarded-For for real client IP
RUN echo '<IfModule remoteip_module>\n\
    RemoteIPHeader X-Forwarded-For\n\
    RemoteIPInternalProxy 172.16.0.0/12\n\
    RemoteIPInternalProxy 192.168.0.0/16\n\
    RemoteIPInternalProxy 10.0.0.0/8\n\
</IfModule>' > /etc/apache2/conf-available/remoteip.conf \
    && a2enconf remoteip

EXPOSE 80
