package net.nextcluster.plugin.server.nukkit;

import cn.nukkit.Server;
import cn.nukkit.command.ConsoleCommandSender;
import cn.nukkit.plugin.PluginBase;
import cn.nukkit.scheduler.Task;
import dev.httpmarco.osgan.utils.data.Pair;
import net.nextcluster.driver.resource.service.ServiceInformation;
import net.nextcluster.plugin.NextClusterPlugin;

public class NukkitClusterPlugin extends PluginBase {
    @Override
    public void onEnable() {
        new ClusterPlugin();
    }

    private static class ClusterPlugin extends NextClusterPlugin {

        public ClusterPlugin() {
            init();
        }

        @Override
        public ServiceInformation currentInformation() {
            return new ServiceInformation(
                    Server.getInstance().getOnlinePlayers().size(),
                    Server.getInstance().getMaxPlayers(),
                    Server.getInstance().getMotd(),
                    Server.getInstance().getOnlinePlayers()
                            .values()
                            .stream()
                            .map(player -> new Pair<>(player.getUniqueId(), player.getName()))
                            .toList()
            );
        }

        @Override
        public void dispatchCommand(String command) {
            Server.getInstance().getScheduler().scheduleTask(new Task() {
                @Override
                public void onRun(int i) {
                    Server.getInstance().dispatchCommand(new ConsoleCommandSender(), command);
                }
            });
        }
    }
}